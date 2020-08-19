<template>
    <div class="container">
        <label>
            <select @change="function (){$refs.films.fetchData(selected)}" name="category" v-model="selected">
                <option value="popular">popular</option>
                <option value="latest">latest</option>
                <option>top_rated</option>
            </select>
        </label>
        <label>
            <input @input="function (){$refs.films.fetchData(message,!!message)}" name="search" type="text" v-model="message">
        </label>

        <films ref="films" v-bind:category="selected"></films>
    </div>
</template>

<script>
export default {
    data () {
        return {
            selected: 'popular',
            message: '',
        }
    }
}
</script>
