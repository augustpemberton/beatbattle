<template>
  <div class="main-layout">
    <navbar />

    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3">
          <chat class="chat sticky-top pt-4" />
        </div>
        <div class="col-md">
          <div class="main container mt-4">
            <child />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from '~/components/Navbar'

export default {
  name: 'MainLayout',

  components: {
    Navbar
  }
}
</script>
<style lang="scss" scoped>
.chat {
  z-index: 1;
}
.main {
  z-index: 100;
}
</style>
