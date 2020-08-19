<template>
    <div class="container">
        <div class="card-columns">
            <div :id="film.id" class="card" v-for="film in films">
                <img :src="film.poster_path?'https://image.tmdb.org/t/p/w500'+film.poster_path:''" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">{{ film.title }} ({{film.release_date}})</h5>
                    <a @click="addOrRemoveFilm(film,$event.target)" class="btn w-100 btn-primary" href="#" v-text="(selected_films_id.indexOf(film.id)<0)?'+':'-'">+</a>
                </div>
            </div>
        </div>
        <div v-bind:style="{position: 'fixed',top: '0',background: (active?'white':'transparent'),right: '0'}">
            <span @click="active=!active" style="font-size:2.5rem;cursor: pointer;padding: 0.4rem;text-align: right;display: block;float: right;">༶</span>
            <ul v-bind:style="{display: (active?'block':'none'), width:'200px', 'overflow-y':'scroll',height:'100vh'}">
                <a @click="saveList" class="btn btn-primary" href="#">Save</a>
                <a @click="getLink" class="btn btn-primary" href="#">Share</a>
                <li v-for="(film, index) in selected_films">
                    <span>#{{++index}} {{ film.title }} ({{film.release_date}}) pop: {{film.popularity}}</span>
                    <img :src="film.poster_path?'https://image.tmdb.org/t/p/w500'+film.poster_path:''" class="card-img-top">
                    <span>{{film.overview}}</span>
                </li>
            </ul>

        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                films: null,
                category: null,
                active:false,
                selected_films: [],
                selected_films_id: [],
            }
        },
        mounted() {
            if (location.hash)
                this.selected_films_id =location.hash.split('#')[1].split('&')
            else if (localStorage.list)
                this.selected_films_id = JSON.parse(localStorage.list);

            if (this.selected_films){
                this.selected_films_id.forEach(film =>
                {
                    axios
                        .get('/api/films/' + film)
                        .then(response => {
                            this.selected_films.push(response.data[0])
                        })
                })
            }
        },
        created() {
            this.fetchData();
        },
        methods: {
            fetchData(category='popular',search=false) {
                axios
                    .get(`/api/films/${category}${search ? '?search=1' : ''}`)
                    .then(response => {
                        this.films = response.data;
                    })
            },
            addOrRemoveFilm(film,btn) {
                let itemPos = this.selected_films_id.indexOf(film.id);
                if (itemPos < 0) {
                    this.selected_films.push(film);
                    this.selected_films_id.push(film.id);
                    btn.text = "-";
                }
                else {
                    this.selected_films.splice(itemPos, 1)
                    this.selected_films_id.splice(itemPos, 1)
                    btn.text = "+";
                }
            },
            saveList(){
                localStorage.list=JSON.stringify(this.selected_films_id)
            },
            getLink(){
                alert(location.href.split('#')[0]+'#'+this.selected_films_id.join('&'))
            }
        },
    }
</script>
