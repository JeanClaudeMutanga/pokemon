<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Pokemons</div>

                    <div class="card-body">

                        <div v-if="fetching">
                            <div class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <h5 class="text-center">Fetching pokemons</h5>
                            </div>
                        </div>

                        <div v-if="!fetching">
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Identifier</th>
                                    <th scope="col">Species ID</th>
                                    <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="d in pokemons.data" v-bind:key="d.id">
                                        <th scope="row">{{d.id}}</th>
                                        <td class="text-right">{{d.identifier}}</td>
                                        <td class="text-right">{{d.species_id}}</td>
                                        <td><button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#PokemonModal" v-on:click="openPokemon(d)">View</button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end">
                                <li class="page-item" :disabled="!prev_page_url">
                                    <a class="page-link" tabindex="-1" aria-disabled ="true" v-on:click="prevPage(prev_page_url)">Previous</a>
                                </li>
                                    <!------
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    -------->
                                <li class="page-item" :disabled="next_page_url == null">
                                    <a class="page-link" v-on:click="nextPage(next_page_url)">Next</a>
                                </li>
                            </ul>
                            </nav>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!--Modal Data-->
        <div class="modal fade" id="PokemonModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Pokemon</h5>
                    <button type="button" class="btn btn-outline-primary btn-sm" aria-label="Edit" v-on:click="editing = true;">Edit</button>
                </div>
                <div class="modal-body">
                    
                    <label for="Identifier">Identifier</label>
                    <div class="input-group flex-nowrap">
                        <input type="text" class="form-control" aria-label="Identifier" aria-describedby="addon-wrapping" v-model="selected.identifier" :disabled ="!editing">
                    </div>

                    <label for="Identifier" class="mt-2">Species</label>
                    <div class="input-group flex-nowrap">
                        <input type="text" class="form-control"  aria-label="Username" aria-describedby="addon-wrapping" v-model="selected.species_id" :disabled ="!editing">
                    </div>

                    <label for="Identifier" class="mt-2">Height</label>
                    <div class="input-group flex-nowrap">
                        <input type="text" class="form-control" aria-label="Username" aria-describedby="addon-wrapping" v-model="selected.height" :disabled ="!editing">
                    </div>

                    <label for="Identifier" class="mt-2">Weight</label>
                    <div class="input-group flex-nowrap">
                        <input type="text" class="form-control" aria-label="Username" aria-describedby="addon-wrapping" v-model="selected.weight" :disabled ="!editing">
                    </div>

                    <label for="Identifier" class="mt-2">Base experience</label>
                    <div class="input-group flex-nowrap">
                        <input type="text" class="form-control" aria-label="Username" aria-describedby="addon-wrapping" id="base_experience" name="base_experience" v-model="selected.base_experience" :disabled ="!editing">
                    </div>

                    <div class="alert alert-success d-flex align-items-center mt-3" role="alert" v-if="saved">
                    <div class="text-center">
                        Pokemon updated successfully
                    </div>
                    </div>

                    <div class="alert alert-danger d-flex align-items-center mt-3" role="alert" v-if="saving_failed">
                    <div class="text-center">
                        Pokemon update failed
                    </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal" v-on:click="resetData()">Close</button>
                    <button type="button" class="btn btn-success btn-sm" :disabled="!editing" v-on:click="savePokemon(selected)">Save</button>
                </div>

                </div>
            </div>
        </div>
    <!---Modal--->
    </div>
</template>

<script>
    export default {
        data:function(){
            return {
                pokemons:[],
                editing:false,
                selected:{},
                saving_failed:false,
                saved:false,
                prev_page_url:null,
                next_page_url:null,
                fetching:true,
                fetched_data:false,

            }
        },
        mounted() {
            this.getPokemons()
        },
        methods:{
            getPokemons(){
                axios.get('/pokemons').then((response)=>{
                    this.pokemons = response.data
                    this.prev_page_url = this.pokemons.prev_page_url
                    this.next_page_url = this.pokemons.next_page_url
                    this.fetching = false
                }).catch((error)=>{
                    this.fetching = false
                })
            },
            openPokemon(d){
                this.selected = d
            },
            resetData(){
                this.saved = false
                this.saving_failed = false
                this.editing = false
                this.selected = {}
                this.loadData()
            },
            savePokemon(d){
                axios.put('/pokemon/'+ d.id,d).then((response)=>{
                    this.saved = true
                    this.loadData()
                }).catch((error)=>{
                    this.saving_failed = true
                })
            },
            nextPage(url){
                this.fetching = true
                axios.get(url).then((response)=>{
                    this.pokemons = response.data
                    this.prev_page_url = this.pokemons.prev_page_url
                    this.next_page_url = this.pokemons.next_page_url
                    this.fetching = false
                }).catch((error)=>{
                    this.fetching = false 
                })
            },
            prevPage(url){
                this.fetching = true
                axios.get(url).then((response)=>{
                    this.pokemons = response.data
                    this.prev_page_url = this.pokemons.prev_page_url
                    this.next_page_url = this.pokemons.next_page_url
                    this.fetching = false
                }).catch((error)=>{
                    this.fetching = false
                })
            },
            loadData(){
                axios.get('http://127.0.0.1:8001/pokemons?page='+this.pokemons.current_page).then((response)=>{
                    this.pokemons = response.data
                    this.prev_page_url = this.pokemons.prev_page_url
                    this.next_page_url = this.pokemons.next_page_url
                    this.fetching = false
                }).catch((error)=>{
                    this.fetching = false
                })
            }
        }
    }
</script>
