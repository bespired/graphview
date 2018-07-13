<template>
    <div class="node"
        :class="[{ selected : node.selected , edge : node.on_edge , moving : node.moving }, node.type]"
        :style="node.position"
        :data-suid="node.suid"
        >
        <span>{{ node.name }}</span>
    </div>
</template>

<script>
     export default {
        name: 'DrawNode',
        props: ['node'],
    }
</script>
