<template>
  <div :style="outerStyle">
    <div
      :style="panelStyle"
      :class="className"
      role="toolbar"
      aria-label="Application dock"
      @mousemove="handleMouseMove"
      @mouseleave="handleMouseLeave"
    >
      <DockItem
        v-for="(item, index) in items"
        :key="index"
        :onClick="item.onClick"
        :className="item.className"
        :mouseX="mouseX"
        :spring="spring"
        :distance="distance"
        :magnification="magnification"
        :baseItemSize="baseItemSize"
        :item="item"
      />
    </div>
  </div>
</template>

<script lang="ts">
import { AnimatePresence, Motion, useMotionValue, useSpring, useTransform } from 'motion-v';
import type { ConcreteComponent, CSSProperties, PropType } from 'vue';
import { computed, defineComponent, h, onMounted, onUnmounted, ref, watch } from 'vue';

const MotionComponent = Motion as unknown as ConcreteComponent;
const AnimatePresenceComponent = AnimatePresence as unknown as ConcreteComponent;

export type SpringOptions = NonNullable<Parameters<typeof useSpring>[1]>;

export type DockItemData = {
  icon: unknown;
  label: unknown;
  onClick: () => void;
  className?: string;
};

export type DockProps = {
  items: DockItemData[];
  className?: string;
  distance?: number;
  panelHeight?: number;
  baseItemSize?: number;
  dockHeight?: number;
  magnification?: number;
  spring?: SpringOptions;
};

const DockIcon = defineComponent({
  name: 'DockIcon',
  props: {
    className: { type: String, default: '' }
  },
  render() {
    return h(
      'div',
      {
        style: {
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center'
        } satisfies CSSProperties,
        class: this.className
      },
      this.$slots.default?.()
    );
  }
});

const DockLabel = defineComponent({
  name: 'DockLabel',
  props: {
    className: { type: String, default: '' },
    isHovered: {
      type: Object as PropType<ReturnType<typeof useMotionValue<number>>>,
      required: true
    }
  },
  setup(props) {
    const isVisible = ref(false);
    let unsubscribe: (() => void) | null = null;

    onMounted(() => {
      unsubscribe = props.isHovered.on('change', (latest: number) => {
        isVisible.value = latest === 1;
      });
    });

    onUnmounted(() => {
      unsubscribe?.();
    });

    return { isVisible };
  },
  render() {
    const labelStyle: CSSProperties = {
      position: 'absolute',
      left: '50%',
      bottom: '100%',
      transform: 'translateX(-50%)',
      marginBottom: '0.5rem',
      width: 'fit-content',
      whiteSpace: 'pre',
      borderRadius: '0.375rem',
      border: '1px solid var(--border)',
      backgroundColor: 'var(--popover)',
      padding: '0.125rem 0.5rem',
      fontSize: '0.75rem',
      color: 'var(--popover-foreground)'
    };

    return h(AnimatePresenceComponent, {}, () =>
      this.isVisible
        ? [
            h(
              MotionComponent,
              {
                key: 'label',
                as: 'div',
                class: this.className,
                role: 'tooltip',
                style: labelStyle,
                initial: { opacity: 0, y: 0 },
                animate: { opacity: 1, y: -10 },
                exit: { opacity: 0, y: 0 },
                transition: { duration: 0.2 }
              },
              () => this.$slots.default?.()
            )
          ]
        : []
    );
  }
});

const DockItem = defineComponent({
  name: 'DockItem',
  props: {
    className: { type: String, default: '' },
    onClick: { type: Function as PropType<() => void>, default: () => {} },
    mouseX: {
      type: Object as PropType<ReturnType<typeof useMotionValue<number>>>,
      required: true
    },
    spring: { type: Object as PropType<SpringOptions>, required: true },
    distance: { type: Number, required: true },
    baseItemSize: { type: Number, required: true },
    magnification: { type: Number, required: true },
    item: { type: Object as PropType<DockItemData>, required: true }
  },
  setup(props) {
    const itemRef = ref<HTMLDivElement>();
    const isHovered = useMotionValue(0);
    const currentSize = ref(props.baseItemSize);

    const mouseDistance = useTransform(props.mouseX, (val: number) => {
      const rect = itemRef.value?.getBoundingClientRect() ?? { x: 0, width: props.baseItemSize };
      return val - rect.x - props.baseItemSize / 2;
    });

    const targetSize = useTransform(mouseDistance, (dist: number) => {
      const { baseItemSize, magnification, distance } = props;
      const clamped = Math.max(-distance, Math.min(distance, dist));
      const t = 1 - Math.abs(clamped) / distance;
      return baseItemSize + (magnification - baseItemSize) * t;
    });

    const size = useSpring(targetSize, props.spring);

    watch(
      () => props.baseItemSize,
      newSize => {
        currentSize.value = newSize;
        size.set(newSize);
      }
    );

    let unsubscribeSize: (() => void) | null = null;

    onMounted(() => {
      unsubscribeSize = size.on('change', (latest: number) => {
        currentSize.value = latest;
      });
    });

    onUnmounted(() => {
      unsubscribeSize?.();
    });

    const handleHoverStart = () => isHovered.set(1);
    const handleHoverEnd = () => isHovered.set(0);
    const handleFocus = () => isHovered.set(1);
    const handleBlur = () => isHovered.set(0);

    return {
      itemRef,
      currentSize,
      isHovered,
      handleHoverStart,
      handleHoverEnd,
      handleFocus,
      handleBlur
    };
  },
  render() {
    const icon = typeof this.item.icon === 'function' ? (this.item.icon as () => unknown)() : this.item.icon;
    const label = typeof this.item.label === 'function' ? (this.item.label as () => unknown)() : this.item.label;

    const itemStyle: CSSProperties = {
      width: `${this.currentSize}px`,
      height: `${this.currentSize}px`,
      position: 'relative',
      display: 'inline-flex',
      alignItems: 'center',
      justifyContent: 'center',
      borderRadius: '10px',
      backgroundColor: 'rgba(128, 128, 128, 0.15)',
      border: '1px solid var(--border)',
      cursor: 'pointer',
      outline: 'none',
      color: 'var(--foreground)'
    };

    return h(
      'div',
      {
        ref: 'itemRef',
        style: itemStyle,
        class: this.className,
        tabindex: 0,
        role: 'button',
        'aria-haspopup': 'true',
        onMouseenter: this.handleHoverStart,
        onMouseleave: this.handleHoverEnd,
        onFocus: this.handleFocus,
        onBlur: this.handleBlur,
        onClick: this.onClick
      },
      [
        h(DockIcon, {}, () => [icon]),
        h(DockLabel, { isHovered: this.isHovered }, () => [typeof label === 'string' ? label : label])
      ]
    );
  }
});

export default defineComponent({
  name: 'Dock',
  components: { DockItem },
  props: {
    items: { type: Array as PropType<DockItemData[]>, required: true },
    className: { type: String, default: '' },
    distance: { type: Number, default: 200 },
    panelHeight: { type: Number, default: 68 },
    baseItemSize: { type: Number, default: 50 },
    dockHeight: { type: Number, default: 256 },
    magnification: { type: Number, default: 70 },
    spring: {
      type: Object as PropType<SpringOptions>,
      default: () => ({ mass: 0.1, stiffness: 150, damping: 12 })
    }
  },
  setup(props) {
    const mouseX = useMotionValue(Infinity);

    const handleMouseMove = (event: MouseEvent) => {
      mouseX.set(event.pageX);
    };

    const handleMouseLeave = () => {
      mouseX.set(Infinity);
    };

    const outerStyle = computed<CSSProperties>(() => ({
      position: 'sticky',
      bottom: '0.5rem',
      display: 'flex',
      justifyContent: 'center',
      pointerEvents: 'none',
      zIndex: 50,
      paddingBottom: '0.5rem'
    }));

    const panelStyle = computed<CSSProperties>(() => ({
      height: `${props.panelHeight}px`,
      display: 'flex',
      alignItems: 'flex-end',
      width: 'fit-content',
      gap: '1rem',
      borderRadius: '1rem',
      backgroundColor: 'rgba(128, 128, 128, 0)',
      backdropFilter: 'blur(20px) saturate(250%)',
      border: '1px solid rgba(128, 128, 128, 0.25)',
      boxShadow: '0 8px 32px rgba(0, 0, 0, 0.2)',
      padding: '0 0.5rem 0.5rem',
      pointerEvents: 'auto'
    }));

    return {
      mouseX,
      outerStyle,
      panelStyle,
      handleMouseMove,
      handleMouseLeave
    };
  }
});
</script>
