<template>
	<div class="container-fluid">
	
			<h3> Our last posts </h3>
	
			<div class="alert alert-info d-flex align-items-center" role="alert" v-if="$root.isLoggedin && !$root.user.is_verified">
				You need to verify your email address to be able to share posts and comment on other posts!
			</div>

			<div class="container-fluid">
	
				<input type="text" class="mt-1 mb-1 form-control" v-model="searchTerm" @input="searchPosts" placeholder="Search for posts...">

				<div v-if="postsLength <= 0" class="card  mt-2 bg-primary border-0 text-white mb-3">
				
				<div class="card-body">
	
					<h3> No posts found </h3>
	
				</div>
	
			</div>
	
			<post-modal v-if="$root.isLoggedin && $root.user.is_verified" @callback="fetchPosts"></post-modal>

			<div v-if="postsLength > 0">
	
			<p class="text-left fw-light">Found {{ postsLength }} Posts</p>
	
			</div>
			<div class="card mb-1 mt-2 bg-light text-black" v-for="post in posts" v-bind:key="post.id" >
					
					<div class="card-body">
				
						<div class="card-title bg-dark text-white p-1 rounded row"><h4 class="text-start col"> {{post.title}}</h4>  <p class="text-end col">Posted {{ moment(post.created_at).format('DD-MM-YYYY') }}</p> </div>
	
							<p class="card-text bg-white rounded p-3"> {{post.description}} </p>
											
						<div class="row mt-3 ml-1 w-100 p-2">
							<comment-section v-bind:itemID="post.id" @callback="fetchPosts"></comment-section>
						</div>
	
					</div>
				
					<div class="card-footer text-muted text-center">
						Posted by <router-link :to="'profile/'+post.user_id" tag="a" class="text-decoration-none"> <a href="#"> {{post.user}} </a> </router-link>
					</div>
			</div>
		</div>
	</div>
	</template>
	
	<script>
		export default {
			data() {
				return {
					moment: this.$root.moment,
					posts: {},
					sorting: {},
					searchTerm: ''
				}
			},
	
			/**
				* 
				*/
			
			watch: {},
	
			/** 
				*/
	
			created() {
				this.fetchPosts();
			},
	
			/**
				*  Methods
				* 
				*/
	
			methods: {
				/**
					*  Fetch every post from the database
					* 
					*/
				fetchPosts: function() {
	
				this.axios .get('/api/posts').then(response => {
	
					this.posts = response.data;
					
					});
	
				},
	
				/**
					* Search posts
					* 
					*/
				
				searchPosts: function() {

				this.axios.get('/api/postSearch', { params: { term: this.searchTerm } }).then(response => {
					this.posts = response.data;
				});
				}
	
			},
	
			computed : {
				postsLength: function() {
					return this.posts.length;
				}
			}
	
		}
	</script>