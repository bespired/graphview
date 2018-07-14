<template>
    <div>
        <div class="header">
            <h1 class="header--title">Graphview</h1>
        </div>
        <table class="index">
            <tr v-for="(row, idx) in index" @click="graph(row.suid)">
                <td>{{ row.name }}</td>
                <td class="action">
                    <a class="button right" @click.stop="dump(row)">Export <i class="icon-export"/></a>
                    <a class="button right" @click.stop="scaffold(row)">Scaffold <i class="icon-rocket"/></a>
                    <a class="button right" @click.stop="remove(row.suid)">Delete <i class="icon-trash"/></a>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td class="action">
                    <a class="button right" @click.stop="create()">Create</a>
                </td>
            </tr>
        </table>
        <alert-success/>
    </div>
</template>

<script>

    import AlertSuccess  from '@Components/alerts/AlertSuccess';

    export default {

        name: 'IndexComponent',

        components: {
            AlertSuccess
        },

        data(){
            return {
                index: global.vuedata.index
            };
        },
        methods: {

            graph(suid){
                window.location = `/_/graphview/${suid}/build`;
            },

            create(){
                axios({
                    url: `/_/graphview/build/create`,
                    method: 'GET',
                }).then((response) => {
                    // console.log(response);
                    window.location = `/_/graphview/${response.data}/build`;
                });
            },

            remove(suid){
                axios({
                    url: `/_/graphview/${suid}/build/delete`,
                    method: 'GET',
                }).then((response) => {
                    _.remove(this.index, function(n) {
                        return n.suid == suid;
                    });
                    this.index.splice(0,0);
                    this.$root.$emit('alert-success', 'Schema removed.');
                });
            },

            scaffold(row){
                const suid = row.suid;
                axios({
                    url: `/_/graphview/${suid}/build/scaffold`,
                    method: 'GET',
                }).then((response) => {
                    this.$root.$emit('alert-success', 'Schema scaffolded.');
                });
            },

            dump(row){
                const suid = row.suid;
                const filename =row.name.replace(' ','') + '.yml';
                axios({
                    url: `/_/graphview/${suid}/build/export`,
                    method: 'GET',
                    responseType: 'blob', // important
                }).then((response) => {
                    console.log(response);
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                    this.$root.$emit('alert-success', 'Schema download.');
                });
            },

        }
    }
</script>
