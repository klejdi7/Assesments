<template>

<div>

	<div class="card mx-auto w-100 mt-2 bg-primary border-0 text-white mb-3">

		<div class="card-body">

			<h4 class="card-title mb-2"> User Details </h4>

			<div class="rounded bg-light text-dark p-2 mb-1">

				<h5>Name: {{ user.name }}</h5>

			</div>

			<div class="rounded bg-light text-dark p-2 mb-1">

				<h5>Username: {{ user.username }}</h5>

			</div>

			<div class="rounded bg-light text-dark p-2 mb-1">

				<h5>Joined at: {{ moment(user.created_at).format('DD-MM-YYYY') }}</h5>

			</div>

			<div class="rounded bg-light text-dark p-2 mb-1">

				<h5>Status: {{ emailVerified }} </h5>

				<div v-if="$root.isLoggedin && $root.user.id == id && !user.email_verified_at">
					<button type="button" class="btn btn-sm me-2 btn-primary" style="margin-left: 20px" @click.prevent="sendEmailVerification()" >Click here to send email verification</button>
					<div class="alert alert-success d-flex align-items-center" role="alert" v-if="emailV">
						Email sent Successfully!
					</div>
				</div>

			</div>

		</div>

	</div>
	
	<div class="container-fluid">

		<h3> {{user.name}}'s Posts</h3>

		<p class="text-left fw-light">Found {{ postsLength}} Posts</p>
		
		<div class="alert alert-info d-flex align-items-center" role="alert" v-if="!user.email_verified_at">
			You need to verify your email address to be able to share posts and comment on other posts!
		</div>

		<post-modal v-if="$root.isLoggedin && $root.user.id == id && user.email_verified_at" @callback="fetchPosts"></post-modal>

		<div class="card mb-1 mt-2 bg-light text-black" v-for="post in posts" :key="post.id">
				
			<div class="card-body">
		
				<div class="card-title bg-dark text-white p-1 rounded row"><h4 class="text-start col"> {{post.title}}</h4>  <p class="text-end col">Posted {{ moment(post.created_at).format('DD-MM-YYYY') }}</p> </div>

				<p class="card-text bg-white rounded p-3"> {{post.description}} </p>
				
				<comment-section v-bind:itemID="post.id" @callback="fetchPosts"></comment-section>

			</div>
		
			<div class="card-footer text-muted" v-if="$root.isLoggedin && $root.user.id == id" >
					
				<div class="d-flex bd-highlight mb-3">

				<h5 class="p-2 bd-highlight"> Options: </h5>

				
				<div class="d-flex justify-content-start" v-if="$root.isLoggedin == true && $root.user.id === post.user_id && !post.hasComments">
					<edit-modal v-bind:itemID="post.id" v-bind:ptitle="post.title" v-bind:pdesc="post.description" @callback="fetchPosts"></edit-modal>
					<button type="button" class="btn btn-sm me-2 btn-danger" style="margin-left: 20px" @click.prevent="deleteItem(post.id)" >Delete Post</button>
				</div>

			</div>

			</div>

			<div class="card-footer text-muted text-center" v-else >
				Posted by <router-link :to="'/profile/'+id" tag="a" class="text-decoration-none"> <a href="#"> {{post.user}} </a> </router-link>
			</div>

		</div>

	</div>	
</div>
</template>

<script>
	export default {
	data() {
		return  {
			id: this.$route.params.id,
			user: [],
			moment: this.$root.moment,
			posts: {},
			sorting: {},
			emailV: false,
			searchTerm: ''
		}
	},

	watch: { },
	
	mounted: function() {
		this.fetchUser();
		this.fetchPosts();
	},

	/**
	 * Methods
	 * 
	 * 
	 * 
	 */

	methods : {

		/**
		 * Method for fetching User details based on the parameter given id
		 * 
		 */
		fetchUser: function() {

			this.axios .get(`/data/getUsername/${this.id}`).then(response => {

				this.user = response.data[0];
				});
			},

		/**
		 * Fetch all posts related to the user
		 * 
		 */
		fetchPosts: function() {

			this.axios .get(`/api/userPost/${this.id}`).then(response => {

				this.posts = response.data;
				
				});
			},

		/**
		 * Send email
		 * 
		 */
		sendEmailVerification: function() {
			this.axios.get(`/api/email-verification/${this.$root.user.id}/1`).then(response => {
				this.emailV = true;
			});
		},

		/**
		 * Delete a post
		 * 
		 */

		 deleteItem: function(id) {

			const confirmed = window.confirm("Are you sure you want to delete this post?");

			if (confirmed) {
				this.axios.delete(`/api/posts/${id}`).then(response => {
					this.success = true;
					this.errors = [];
					this.$emit(this.fetchPosts());
				});
			}
		},

	},

	computed : {
			postsLength: function() {
				return this.posts.length;
			},
			emailVerified: function() {
				return this.user.email_verified_at != null ? "Email verified" : "Not verified";
			}
		}

}
</script>