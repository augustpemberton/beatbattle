<template>
  <div class="container">
    <div class="large-12 medium-12 small-12 filezone">
      <input id="files" ref="files" type="file" multiple @change="handleFiles()">
      <p> Drop your files here <br>or click to search </p>
    </div>

    <ul class="list-group">
      <li v-for="(file,key) in files" :key="key" class="list-group-item d-flex justify-content-between align-items-center">
        {{ file.name }}
        <button class="btn btn-danger btn-xs" @click="removeFile(key)">
          <fa icon="times" fixed-width />
        </button>
      </li>
    </ul>
  </div>
</template>

<script>
import Swal from 'sweetalert2'
export default {
  name: 'SampleUpload',
  props: {
    'value': {
      type: Array,
      default: null
    },
    'maxFiles': {
      type: Number,
      default: 1
    }
  },

  data () {
    return {
      files: []
    }
  },

  methods: {
    handleFiles () {
      let uploadedFiles = this.$refs.files.files

      if (this.files.length + uploadedFiles.length > this.maxFiles) {
        Swal.fire({
          type: 'error',
          title: 'Too many files!',
          text: 'You can only upload up to ' + this.maxFiles + ' files.',
          reverseButtons: true,
          confirmButtonText: 'Ok'
        })
      } else {
        for (var i = 0; i < uploadedFiles.length; i++) {
          this.files.push(uploadedFiles[i])
        }
        this.$emit('input', this.files)
      }
    },

    removeFile (key) {
      this.files.splice(key, 1)
      this.$emit('input', this.files)
    }
  }
}
</script>

<style scoped>
    input[type="file"]{
        opacity: 0;
        width: 100%;
        height: 200px;
        position: absolute;
        cursor: pointer;
    }
    .filezone {
        outline: 2px dashed grey;
        outline-offset: -10px;
        background: #ccc;
        color: dimgray;
        padding: 10px 10px;
        min-height: 200px;
        position: relative;
        cursor: pointer;
    }
    .filezone:hover {
        background: #c0c0c0;
    }

    .filezone p {
        font-size: 1.2em;
        text-align: center;
        padding: 50px 50px 50px 50px;
    }
    div.file-listing img{
        max-width: 90%;
    }

    div.file-listing{
        margin: auto;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    div.file-listing img{
        height: 100px;
    }
    div.success-container{
        text-align: center;
        color: green;
    }

    div.remove-container{
        text-align: center;
    }

    div.remove-container a{
        color: red;
        cursor: pointer;
    }

    a.submit-button{
        display: block;
        margin: auto;
        text-align: center;
        width: 200px;
        padding: 10px;
        text-transform: uppercase;
        background-color: #CCC;
        color: white;
        font-weight: bold;
        margin-top: 20px;
    }
</style>
