<template>
  <vue-countdown-timer
    :start-time="startTime"
    :end-time="endTime"
    :interval="1000"
    :start-label="startLabel"
    :end-label="endLabel"
    label-position="begin"
    :end-text="'Event ended'"
    :day-txt="'days'"
    :hour-txt="'hours'"
    :minutes-txt="'minutes'"
    :seconds-txt="null"
    @start_callback="timerStart"
    @end_callback="timerEnd"
  >
    <template slot="start-label" slot-scope="scope">
      <span v-if="scope.props.startLabel !== '' && scope.props.tips && scope.props.labelPosition === 'begin'" style="color: red">
        {{ scope.props.startLabel }}:
      </span>
      <span v-if="scope.props.endLabel !== '' && !scope.props.tips && scope.props.labelPosition === 'begin'" style="color: blue">
        {{ scope.props.endLabel }}:
      </span>
    </template>
    <template slot="countdown" slot-scope="scope">
      <span>{{ scope.props.days }}</span><i>{{ scope.props.dayTxt }}</i>
      <span>{{ scope.props.hours }}</span><i>{{ scope.props.hourTxt }}</i>
      <span>{{ scope.props.minutes }}</span><i>{{ scope.props.minutesTxt }}</i>
    </template>
    <template slot="end-label" slot-scope="scope">
      <span v-if="scope.props.startLabel !== '' && scope.props.tips && scope.props.labelPosition === 'end'" style="color: red">
        {{ scope.props.startLabel }}:
      </span>
      <span v-if="scope.props.endLabel !== '' && !scope.props.tips && scope.props.labelPosition === 'end'" style="color: blue">
        {{ scope.props.endLabel }}:
      </span>
    </template>
    <template slot="end-text" slot-scope="scope">
      <span style="color: green">{{ scope.props.endText }}</span>
    </template>
  </vue-countdown-timer>
</template>

<script>
export default {
  name: 'BattleCountdown',
  props: {
    startTime: {
      type: String,
      default: null
    },
    endTime: {
      type: String,
      default: null
    },
    startLabel: {
      type: String,
      default: null
    },
    endLabel: {
      type: String,
      default: null
    }
  },
  methods: {
    timerStart () {
      this.$emit('timer-start')
    },
    timerEnd () {
      this.$emit('timer-end')
    }
  }
}
</script>
