<template>
  <div class="stage perspective-off">
    <div class="rotate">
      <div
        v-for="index in slots"
        :id="'ring-'+id+'-'+(index-1)"
        :key="index"
        class="ring"
      />
    </div>
  </div>
</template>
<script>
import $ from 'jquery'

export default {
  name: 'Spinner',
  props: {
    number: {
      type: Number,
      default: null
    }
  },
  data () {
    return {
      id: null,
      slots_per_reel: 10,
      reel_radius: 80
    }
  },
  computed: {
    digits () {
      if (this.number === null) return ''
      return this.pad(this.number.toString(), 2)
    },
    slots () {
      return this.digits.length
    }
  },
  watch: {
    number (newVal) {
      this.spin(0)
    }
  },
  mounted () {
    this.id = this._uid
    this.$nextTick(function () {
      if (this.number != null) {
        this.spin(1)
      }
    })
  },
  methods: {
    spin (timer) {
      for (var i = 0; i < this.digits.length; i++) {
        var ringID = '#ring-' + this.id + '-' + i
        console.log($(ringID))
        this.createSlots($(ringID))
        $(ringID)
          .css('animation', 'back-spin 1s, spin-' + this.digits[i] + ' ' + (timer + i) + 's')
          .attr('class', 'ring spin-' + this.digits[i])
      }
    },
    createSlots (ring) {
      console.log('creating slots:' + ring)
      var slotAngle = 360 / this.slots_per_reel

      for (var i = 0; i < this.slots_per_reel; i++) {
        var slot = document.createElement('div')
        slot.className = 'slot'

        // compute and assign the transform for this slot
        var transform = 'rotateX(' + (slotAngle * i) + 'deg) translateZ(' + this.reel_radius + 'px)'

        slot.style.transform = transform

        // setup the number to show inside the slots
        // the position is randomized to

        $(slot).append('<p>' + (i % 10) + '</p>')

        // add the poster to the row
        ring.append(slot)
      }
    },
    pad (n, width, z) {
      z = z || '0'
      n = n + ''
      return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n
    }
  }
}
</script>
<style lang="scss">

.stage {
  margin: 0 auto;
  overflow: hidden;
}

.perspective-on {
  -webkit-perspective: 1000px;
     -moz-perspective: 1000px;
          perspective: 1000px;
}
.perspective-off {
  -webkit-perspective: 0;
     -moz-perspective: 0;
          perspective: 0;
}

.rotate {
  margin: 0 auto 0;
  height: 50px;
  transform-style: preserve-3d;
  float: right;
}

.ring {
  margin: 0 auto;
  height: 52px;
  width: 25px;
  float: left;
  transform-style: preserve-3d;
}
.slot {
  position: absolute;
  height: 52px;
  width: 25px;
  box-sizing: border-box;
  opacity: 1;
  background: #FFF;
}

.slot p {
  font-size: 36px;
  font-weight: bold;
  line-height: 80px;
  margin: 0;
  text-align: center;
}

.tilted {
  transform: rotateY(45deg);
}
/*=====*/
@for $i from 0 to 10 {
  .spin-#{$i} {
    transform: rotateX((-36 * $i) - 3593deg)
  }
  @keyframes spin-#{$i} {
    0%    { transform: rotateX(0deg); }
    100%  { transform: rotateX((-36 * $i) - 3593deg); }
  }
}
/*=====*/
@keyframes back-spin {
    /*0%    { transform: rotateX(0deg); }*/
    100%  { transform: rotateX(30deg); }
}
</style>
