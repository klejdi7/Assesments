<template>

	<div class="container mt-2">

		<h2>Comment Section</h2>
		
		<div class="container mt-3 comments w-100">

			<div class="overflow-auto w-100" style="max-height: 300px;">

				<div v-for="(comment, index) in comments" :key="index" class="comments mb-3 p-3 bg-secondary text-light rounded w-100">

					<div class="p-3 bg-light text-dark rounded mb-2"> 

						<p><strong>{{ comment.username }}: </strong> {{ comment.comment }}</p> 
						<p>Posted on {{ moment(comment.created_at).format('DD-MM-YYYY') }}</p> 

						<div class="d-flex justify-content-start" v-if="$root.isLoggedin == true && $root.user.id === comment.user_id">

							<comment-modal v-bind:action="'Edit'" v-bind:itemID="comment.id" v-bind:comment="comment.comment" @callback="fetchComments"></comment-modal>
							<button type="button" class="btn btn-sm me-2 btn-danger" @click.prevent="deleteComment(comment.id)" >Delete Comment</button>

						</div>

					</div>

				</div>

			</div>

		</div>

		<div class="alert alert-info d-flex align-items-center" role="alert" v-if="$root.isLoggedin && !$root.user.is_verified">
				You need to verify your email address to be able to share posts and comment on other posts!
		</div>

		<form class="mb-4" v-if="$root.isLoggedin && $root.user.is_verified">

			<div class="mb-3">

				<label for="comment" class="form-label">Your Comment</label>

				<textarea v-model="newComment" class="form-control" id="comment" rows="3" placeholder="Write a comment..."></textarea>

			</div>

			<button type="submit" class="btn btn-primary" @click.prevent="addComment">Post Comment</button>
			
		</form>

	</div>

</template>

<script>
export default {

	props : {
		itemID : {
			type: Number
		},
	},

	data () {
		return {
			newComment: '',
			comments: [],
			moment: this.$root.moment,
		}
	},

	watch : {},

	mounted() {
		console.log("Received itemID:", this.itemID);
	},

	created: function () {
		this.fetchComments();
	},

	methods: {
		
		fetchComments: function() {
			console.log("Fetching...");
			console.log(this.itemID);
			this.axios .get(`/api/postComments/${this.itemID}`).then(response => {
				this.comments = response.data.comments;
				console.log(this.comments.comment);
			});

		},
		
		addComment: function(){
					
			var formData = new FormData();
			formData.append('post_id', this.itemID);
			formData.append('user_id', this.$root.user.id);
			formData.append('comment', this.newComment);

			this.axios.post('/api/addComment', formData).then(response => {    
				this.$emit('callback');
				this.$emit(this.fetchComments());
				this.newComment = '';
					})
				.catch(e => {
			}); 
		},
		deleteComment: function(commentID) {

			const confirmed = window.confirm("Are you sure you want to delete this comment?");

			if (confirmed) {
				this.axios.delete(`/api/delete-comment/${commentID}`).then(response => {
					this.success = true;
					this.errors = [];
					this.$emit(this.fetchComments());
					
				});
			}
		}

	}

}
</script>