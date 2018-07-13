<template>
    <div :class="[{'show':show},color]">
        {{ label }}
        <a @click="close()">x</a>
    </div>
</template>

<script>

    export default {

        data(){
            return {
                color: 'alert-success',
                show: false,
                label: '',
                timex: 0,
            };
        },

        mounted() {
            this.$root.$on('alert-success', (event)=>{
                this.color = 'alert-success';
                this.label = event;
                this.show  = true;
                this.timex = setTimeout(()=>{
                    this.show = false;
                }, 1700);

            });
            this.$root.$on('alert-error', (event)=>{
                this.color = 'alert-error';
                this.label = event;
                this.show  = true;
            });
        },

        methods: {
            close(){
                this.show = false;
            },
        }
    }
</script>
