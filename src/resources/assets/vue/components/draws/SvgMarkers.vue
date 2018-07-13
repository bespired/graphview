<template>
    <svg class="defs">
        <defs>
            <marker id="arrow-start" markerWidth="10" markerHeight="10" refX="0" refY="3" orient="auto" markerUnits="strokeWidth">
                <path d="M9,0 L9,6 L0,3 z" />
            </marker>

            <marker id="arrow-end" markerWidth="10" markerHeight="10" refX="6" refY="3" orient="auto" markerUnits="strokeWidth">
                <path d="M0,0 L0,6 L9,3 z" />
            </marker>
            <marker id="hover-arrow-start" markerWidth="10" markerHeight="10" refX="0" refY="3" orient="auto" markerUnits="strokeWidth">
                <path d="M9,0 L9,6 L0,3 z" />
            </marker>
            <marker id="hover-arrow-end" markerWidth="10" markerHeight="10" refX="6" refY="3" orient="auto" markerUnits="strokeWidth">
                <path d="M0,0 L0,6 L9,3 z" />
            </marker>
            <marker id="hover-start" markerWidth="8" markerHeight="8" refX="0" refY="5" orient="auto" >
                <circle cx="5" cy="5" r="3"></circle>
            </marker>
            <marker id="hover-end" markerWidth="8" markerHeight="8" refX="6" refY="5" orient="auto" >
                <circle cx="5" cy="5" r="3"></circle>
            </marker>
        </defs>
    </svg>
</template>

<script>
     export default {
        name: 'SvgMarkers'
    }
</script>
