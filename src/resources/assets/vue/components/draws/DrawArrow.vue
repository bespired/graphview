<template>
    <svg class="edge"
            :class     = "[ arrow.hover, arrow.count]"
            :width     = "arrow.width"
            :height    = "arrow.height"
            :style     = "arrow.position"
            :data-suid = "arrow.suid" >
        <line
            :x1="arrow.lp1.x"
            :y1="arrow.lp1.y"
            :x2="arrow.lp2.x"
            :y2="arrow.lp2.y"
            :class="arrow.style"
        />
    </svg>
</template>

<script>
     export default {
        name: 'DrawArrow',
        props: ['arrow'],
    }
</script>
