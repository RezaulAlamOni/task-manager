


<template>
  <li class="folder" v-bind:class="[folder.leaf ? 'is-leaf' : 'is-folder']">
    <span v-on:click="expand()">{{ folder.text }}</span>

    <ul class="sub-folders" v-if="folder.children && folder.children.length > 0" v-show="folder.expanded">
      <folder v-for="child in folder.children" v-bind:folder="child"></folder>
    </ul>
    <div class="folder-empty" v-else v-show="!folder.leaf && folder.expanded">No Data</div>
  </li>
</template>
<script>
import Folder from './recurLi.vue';

export default {
  name: 'root',
  props: {
    folder: Object
  },
  components: {
    Folder
  }
};
</script>