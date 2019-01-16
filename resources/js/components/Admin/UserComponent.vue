<template>
	<v-layout row>
		<v-flex xs12 class="m-3 px-3">
			<v-app id="inspire">
				<v-card>
					<v-card-title class="grey lighten-4">
						<v-icon class="pr-2">{{ $route.meta.icon }}</v-icon> {{ $route.meta.name }} {{ /* 社員管理 */ }}
						<v-spacer></v-spacer>
						<v-spacer></v-spacer>
						<v-spacer></v-spacer>
						<v-spacer></v-spacer>
						<v-spacer></v-spacer>
						<v-text-field v-on:keyup.enter="fetchAll()" v-model="search" md4 append-icon="search" label="Search" single-line
						 hide-details></v-text-field>
						<v-btn @click.prevent="datatbleSearch()" fab dark small color="primary">
							<v-icon dark>search</v-icon>
						</v-btn>
						<v-btn @click.prevent="clearSearch()" fab dark small color="pink">
							<v-icon dark>close</v-icon>
						</v-btn>
						<v-btn @click.prevent="addNew()" fab dark small color="teal">
							<v-icon dark>add</v-icon>
						</v-btn>
					</v-card-title>

					<v-divider></v-divider>
					<v-data-table :headers="headers" :items="customers" :pagination.sync="pagination" :total-items="totalItems" :loading="loading" :rows-per-page-items="rowsPerPageItems">
						<template slot="items" slot-scope="props">
							<td>{{ props.item.name }}</td>
							<td>{{ props.item.email }}</td>
							<td>{{ props.item.age }}</td>
							<td class="text-xs-center">{{ props.item.created_at }}</td>
							<td class="text-xs-center">{{ props.item.updated_at }}</td>
							<td class="text-xs-center">{{ props.item.active }}</td>
							<td class="text-xs-center">
								<v-icon small class="mr-2" @click="editItem(props.item)">
									edit
								</v-icon>
								<v-icon small @click="deleteItem(props.item)">
									delete
								</v-icon>
							</td>
						</template>
					</v-data-table>
					<div class="mb-2 text-xs-center">
						<v-pagination v-model="pagination.page" :length="lastPage" :total-visible="8" @input="next" circle></v-pagination>
					</div>
				</v-card>
			</v-app>
		</v-flex>

		<v-dialog v-model="dialogInput" max-width="1000px">
			<v-card>
				<v-card-title>
					<span class="headline">{{ formTitle }}</span>
				</v-card-title>
				<v-card-text>
					<v-container grid-list-md>
						<v-layout wrap>
							<v-flex xs12 sm12 md12>
								<v-text-field v-validate="'required'" v-model="customer.name" :counter="10" :error-messages="errors.collect('name')"
								 label="Name" data-vv-name="name" required></v-text-field>
							</v-flex>
							<v-flex xs12 sm12 md12>
								<v-text-field v-validate="'required'" v-model="customer.email" :counter="10" :error-messages="errors.collect('email')"
								 label="Email" data-vv-name="email" required></v-text-field>
							</v-flex>
							<v-flex xs12 sm12 md12>
								<v-text-field v-validate="'required'" v-model="customer.age" :counter="3" :error-messages="errors.collect('age')"
								 label="Age" data-vv-name="age" required></v-text-field>
							</v-flex>
							<v-flex xs12 sm12 md12>
								<v-text-field v-validate="'required'" v-model="customer.address" :counter="10" :error-messages="errors.collect('address')"
								 label="Address" data-vv-name="address" required></v-text-field>
							</v-flex>

							<v-checkbox label="Active" v-model="customer.active"></v-checkbox>
						</v-layout>
					</v-container>
				</v-card-text>

				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="blue darken-1" flat @click.native="close()">Cancel</v-btn>
					<v-btn color="blue darken-1" flat @click.native="save()">Save</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-dialog v-model="dialogConfirmDelete" max-width="500">
			<v-card>
				<v-card-title class="headline">Delete</v-card-title>
				<v-card-text>Delete Text</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="blue darken-1" flat="flat" @click="dialogConfirmDelete = false">
						Disagree
					</v-btn>

					<v-btn color="red darken-1" flat="flat" @click="erase()">
						Agree
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-snackbar v-model="snackbar.active" :bottom="snackbar.y === 'bottom'" :left="snackbar.x === 'left'" :multi-line="snackbar.mode === 'multi-line'"
		 :right="snackbar.x === 'right'" :timeout="snackbar.timeout" :top="snackbar.y === 'top'" :vertical="snackbar.mode === 'vertical'">
			{{ snackbar.message }}
			<v-btn color="pink" flat @click="snackbar.active = false">Close</v-btn>
		</v-snackbar>
	</v-layout>
</template>

<script>
	export default {
		name: "settings-view",
		components: {
		},
		data() {
			return {
				loading: true,
				rowsPerPageItems: [10, 20, 30, 40],
				pagination: {
					rowsPerPage: 10
				},
				headers: [
					{ text: 'Name', align: 'center', value: 'name' },
					{ text: 'Email', align: 'center', value: 'email' },
					{ text: 'Age', align: 'center', value: 'age' },
					{ text: 'Created At', align: 'center', value: 'created_at' },
					{ text: 'Updated At', align: 'center', value: 'updated_at' },
					{ text: 'Active', align: 'center', value: 'active' },
					{ text: "Actions", align: 'center', sortable: false }
				],
				customer: {},
				search: "",
				dialogInput: false,
				customers: [],
				totalItems: 0,
				lastPage: 0,
				dialogConfirmDelete: false,
				edit: false,
				dialogInput: false,
				selectedCategory: null,
				categories: [],
				logo: {
					imageName: "",
					imgInput: "",
					imageFile: "",
				},
				banner: {
					imageName: "",
					imgInput: "",
					imageFile: "",
				},
				snackbar: {
					active: false,
					y: 'bottom',
					x: 'right',
					mode: '',
					timeout: 3000,
					message: ''
				},
				formTitle: ''
			}
		},
		watch: {
			pagination() {
				this.fetchAll()
			}
		},
		created() {
			console.log(this.$store.state)
			//this.fetchAll()
		},
		methods: {
			pickLogo() {
				this.$refs.logo.click()
			},
			pickBanner() {
				this.$refs.banner.click()
			},

			close() {
				this.dialogInput = false
				setTimeout(() => {
					this.editedItem = Object.assign({}, this.defaultItem)
				}, 300)
			},
			save() {
				this.$validator.validate().then(result => {
					if (result) {
						this.loading = true
						if (this.edit) {
							console.log('edit', this.editedItem)
							this.customer.updated_by = this.$store.getters.currentUser.id

							axios.put('/api/customer', this.customer)
								.then(
									(response) => {
										this.showSnackbar('Item updated successfully !')
										this.loading = false
										console.log(response)
										this.fetchAll()
									}
								)
								.catch(
									(error) => {
										console.log(error)
									}
								)
						} else {
							console.log('save', this.editedItem)
							this.customer.created_by = this.$store.getters.currentUser.id
							this.customer.updated_by = this.$store.getters.currentUser.id
							axios.post('/api/customer', this.customer)
								.then(
									(response) => {
										this.showSnackbar('Item added successfully !')
										this.loading = false
										console.log(response)
										this.fetchAll()
									}
								)
								.catch(
									(error) => {
										console.log(error)
									}
								)
						}
						this.close()
						this.edit = false
					}
				})
			},
			onLogoPicked(e) {
				const files = e.target.files
				if (files[0] !== undefined) {
					this.logo = files[0].name
					if (this.logo.lastIndexOf(".") <= 0) {
						return
					}
					const fr = new FileReader()
					fr.readAsDataURL(files[0])
					fr.addEventListener("load", () => {
						this.logo.imgInput = fr.result
						this.logo.imageFile = files[0] 
						this.customer.logo = this.logo.imgInput
					})
				} else {
					this.logo = ""
					this.logo.imageFile = ""
					this.logo.imgInput = ""
				}
			},
			onBannerPicked(e) {
				const files = e.target.files
				if (files[0] !== undefined) {
					this.logo = files[0].name
					if (this.logo.lastIndexOf(".") <= 0) {
						return
					}
					const fr = new FileReader()
					fr.readAsDataURL(files[0])
					fr.addEventListener("load", () => {
						this.logo.imgInput = fr.result
						this.logo.imageFile = files[0] 
						this.customer.banner = this.logo.imgInput
					})
				} else {
					this.logo = ""
					this.logo.imageFile = ""
					this.logo.imgInput = ""
				}
			},
			fetchAll() {
				console.log(this.pagination)
				this.loading = true
				let url = `/api/customers-datatable`
				let params = `?page=${this.pagination.page}
								&rowsPerPage=${this.pagination.rowsPerPage}
								&sortBy=${this.pagination.sortBy}
								&descending=${this.pagination.descending}
								&search=${this.search}`

				axios.get(url + params)
					.then(response => {
						this.customers = response.data.data
						this.totalItems = response.data.meta.total
						this.lastPage = response.data.meta.last_page
						console.log(response.data)
						this.loading = false
					})
					.catch(error => {
						if (error.response) {
							console.log(error.response);
							// if (error.response.status === 401) {
							// 	window.location.href = '/login'
							// }
						}
					})
			},
			erase() {
				this.dialogConfirmDelete = false
				this.loading = true
				axios.delete(`/api/customer/${this.customer.id}`)
					.then(response => {
						this.loading = false
						this.fetchAll()
						this.showSnackbar('Item deleated successfully !')
					})
					.catch(error => {
						if (error.response) {
							console.log(error.response)
						}
					})
			},
			addNew() {
				this.formTitle = "Add Item"
				this.customer = { active: true }
				this.logo.imgInput = ``
				this.selectedCategory = null
				this.dialogInput = true
				this.edit = false
				this.logo.imageName = null
				this.banner.imageName = null
				this.logo.imgInput = null
				this.banner.imgInput = null
			},
			editItem(item) {
				this.formTitle = "Edit Item"
				this.customer = Object.assign({}, item)
				this.dialogInput = true
				this.edit = true
				this.logo.imageName = null
				this.logo.imgInput = this.customer.logo
				this.banner.imageName = null
				this.banner.imgInput = this.customer.banner
			},
			deleteItem(item) {
				this.dialogConfirmDelete = true
				this.customer = item
			},
			pickFileLogo() {
				this.$refs.logo.click()
			},
			onFilePickedLogo(e) {
				const files = e.target.files
				if (files[0] !== undefined) {
					this.logo.imageName = files[0].name
					if (this.logo.imageName.lastIndexOf(".") <= 0) {
						return
					}
					const fr = new FileReader()
					fr.readAsDataURL(files[0])
					fr.addEventListener("load", () => {
						this.logo.imgInput = fr.result
						this.logo.imageFile = files[0]
						this.customer.logo = this.logo.imgInput
					})
				} else {
					this.logo.imageName = ""
					this.logo.imageFile = ""
					this.logo.imgInput = ""
				}
			},
			pickFileBanner() {
				this.$refs.banner.click()
			},
			onFilePickedBanner(e) {
				const files = e.target.files
				if (files[0] !== undefined) {
					this.banner.imageName = files[0].name
					if (this.banner.imageName.lastIndexOf(".") <= 0) {
						return
					}
					const fr = new FileReader()
					fr.readAsDataURL(files[0])
					fr.addEventListener("load", () => {
						this.banner.imgInput = fr.result
						this.banner.imageFile = files[0]
						this.customer.banner = this.banner.imgInput
					})
				} else {
					this.banner.imageName = ""
					this.banner.imageFile = ""
					this.banner.imgInput = ""
				}
			},
			datatbleSearch() {
				if (this.search.length) {
					this.pagination.page = 1
					this.fetchAll()
					console.log(this.pagination)
					this.snackbar = true
				}
			},
			showSnackbar(message) {
				this.snackbar.active = true
				this.snackbar.message = message
			},
			next(page) {
				console.log(page)
				this.pagination.page = page
				this.fetchAll()
			},
			clearSearch() {
				if (this.search.length) {
					this.search = ""
					this.fetchAll()
				}
			}
		}
	}
</script>