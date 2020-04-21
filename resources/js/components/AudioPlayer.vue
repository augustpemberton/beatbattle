<template>
  <div>
    <button class="btn btn-info" @click="playPause">
      <fa v-if="!isPlaying" icon="play" fixed-width />
      <fa v-else icon="pause" fixed-width />
    </button>
    <div :id="'waveform'+id" class="waveform" />
  </div>
</template>

<script>
import WaveSurfer from 'wavesurfer.js'

export default {
  name: 'AudioPlayer',
  props: {
    url: {
      type: String,
      default: null
    },
    height: {
      type: Number,
      default: 128
    }
  },
  data () {
    return {
      wavesurfer: null,
      id: this._uid,
      isPlaying: false
    }
  },
  mounted () {
    this.wavesurfer = WaveSurfer.create({
      container: '#waveform' + this.id,
      height: this.height,
      barWidth: 1,
      barHeight: 1,
      barGap: null,
      cursorWidth: 2,
      cursorHeight: 1,
      hideScrollbar: true,
      responsive: true
    })
    this.wavesurfer.on('play', () => {
      this.isPlaying = true
    })
    this.wavesurfer.on('pause', () => {
      this.isPlaying = false
    })
    this.wavesurfer.load(this.url)
  },
  methods: {
    playPause () {
      this.wavesurfer.playPause()
    }
  }
}
</script>
