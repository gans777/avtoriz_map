<template>
  <div>
  <select v-model="selected">
    <option disabled value="">Выберите один из вариантов</option>
    <option v-for="(element,index) in deficits" :key="index">{{ element.name_of_product }}</option>
  </select>
  <span>Выбрано: {{ selected }}</span>
</div>
</template>

<script>
import axios from 'axios'
export default {
  
  data() {
    return {
      deficits: [],
      selected: ''
    }
  },
  mounted() {
const params = new URLSearchParams();
     params.append('label', 'read_deficit_products'); //читает все дефициты
     axios.post('http://avtorizmap/ajax/ajaxrequest.php', params).then(response => {
     // console.log(response);
     //this.deficits=response.data;
     this.deficits=response.data.slice();
     
     });
  },
  watch: {
    selected: function(){
      console.log("значение категории= "+this.selected);
      this.$emit('change-deficit',{
        this_deficit: this.selected
      });
    }
  } 
  }
</script>

<style scoped>
h1 {
  color: red;
}
</style>
