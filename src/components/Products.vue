<template>
  <select>
    <option v-for="(element,index) in deficits" :key="index">{{ element.name_of_product }}</option>
  </select>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      deficits: []
    }
  },
  mounted() {
const params = new URLSearchParams();
     params.append('label', 'read_deficit_products'); //читает все дефициты
     axios.post('http://avtorizmap/ajax/ajaxrequest.php', params).then(response => {
     // console.log(response);
     //this.deficits=response.data;
     this.deficits=response.data.slice();
      response.data.forEach(function(value){
        console.log(value.name_of_product);
        //this.deficits=slice(value.name_of_product);
      });
     });
  }
  }
</script>

<style scoped>
h1 {
  color: red;
}
</style>
