<template>
	<div class="header">
		<h1 class="header--title">Graphview</h1>
		<input
			id="i-schema-name"
			type="text"
			v-model="name"
			@change="$root.$emit('set-schema-name', name);">
		<a @click="$root.$emit('save-schema')"   class="button right spacer">Save</a>
		<a @click="$root.$emit('index-schema')"  class="button right hover">Index</a>

		<a class="button right hover" v-if="empty">
			<input
			type="file"
			id="file"
			ref="file"
			v-on:change="handleFileUpload()"/>
			Import
		</a>
	</div>
</template>
<style>
#file{
	width: 100%;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    position: absolute;
    transform: scale(10);
    transform-origin: 0% 100%;
    opacity: 0.000001;
   }
</style>
<script>
	export default {
		name: 'HeaderComponent',

		data(){
			return {
				name: global.vuedata.name,
				empty: _.size(global.vuedata.schema.nodes) == 0,
			};
		},

		methods: {
			handleFileUpload(){
				let self = this;
				let formData = new FormData();
				formData.append('file', this.$refs.file.files[0]);
				axios.post( `/_/graphview/${global.vuedata.suid}/build/import`, formData,
					{
						headers: {
							'Content-Type': 'multipart/form-data'
						}
					}
				).then(
					(response) => {
						global.vuedata = response.data;
						self.$root.$emit('alert-success', 'Imported schema.');
						self.$root.$emit('schema-loaded');
						this.empty = false;
						this.name  = response.data.name;
					}
				)
				.catch(function(){
					self.$root.$emit('alert-error', 'Error while importing schema.');
				});

			}
		}
	}
</script>
