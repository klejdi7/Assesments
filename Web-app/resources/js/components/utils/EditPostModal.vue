<template>

	<div>

		<a data-bs-toggle="modal" href="#" type="button" class="btn btn-success" disabled :data-bs-target="'#editPostModal' + itemID" >
		
			Edit Post

		</a>

		<div class="modal fade " :id="'editPostModal' + itemID" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			
			<div class="modal-dialog">
				
				<div class="modal-content">
					
					<div class="modal-header">
						
						<h5 class="modal-title" id="exampleModalLabel">Edit post</h5>
						
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="success = false"></button>
					
					</div>

					<div class="modal-body">
						
						<div class="alert alert-success d-flex align-items-center" role="alert" v-if="success">
								Post edit successfully!
						</div>

						<div class="alert alert-danger d-flex align-items-center" role="alert" v-if="errors !== null" v-for="error in errors">
							{{error}} 
						</div>

							<div>
							
								<form>

									<div class="row mb-3">

										<div class="input-group mb-3">
										
											<span class="input-group-text" id="basic-addon1">Post Title</span>
							
											<input class="form-control" placeholder="Email Post Title please" v-model="post.title" name="title" aria-describedby="basic-addon1">
										
										</div>
									
									</div>

									<div class="row mb-3">
									
										<div class="input-group mb-3">
										
											<span class="input-group-text" id="basic-addon1">Description</span>
							
											<textarea  rows="4" cols="50" class="form-control" placeholder="Email Post description please" v-model="post.description" name="email"  aria-describedby="basic-addon1"></textarea>
										
										</div>
					
									</div>

								</form>

							</div>

						</div>

						<div class="modal-footer">
			
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="success = false" >Close</button>
			
							<button type="button" class="btn btn-success" @click="editPost()">Edit</button>
			
						</div>

					</div>
		
				</div>

			</div>

		</div>

</template>

<script>

export default {

	props : {
		itemID : {
			type: Number
		},
		ptitle: {
			type: String
		},
		pdesc: {
			type: String
		}
	},

	data() {
		return {
		post: {title: this.ptitle, description: this.pdesc},
		success: false,
		errors: null,
		}
	},

	mounted : function(){ },

	watch: {},

	methods: {
		
		editPost: function() {

			var postData = new FormData();
			postData.append('title', this.post.title || " ");
			postData.append('description', this.post.description || " ");

			this.axios.post(`/api/editPost/${this.itemID}`, postData).then(response => {
				this.success = true;
					this.errors = [];
					this.$emit('callback');
				})

				.catch(e => {
					this.errors = e.response.data;
			});
		},
	}
}

</script>