import Vue from 'vue'
import Card from './Card'
import Child from './Child'
import Button from './Button'
import Checkbox from './Checkbox'

import SampleUpload from './SampleUpload'
import CreateBattle from './CreateBattle'
import BattleList from './BattleList'
import BattleCard from './BattleCard'
import { HasError, AlertError, AlertSuccess } from 'vform'

// Components that are registered globaly.
[
  Card,
  Child,
  Button,
  Checkbox,
  HasError,
  AlertError,
  AlertSuccess,

  SampleUpload,
  CreateBattle,
  BattleList,
  BattleCard

].forEach(Component => {
  Vue.component(Component.name, Component)
})
