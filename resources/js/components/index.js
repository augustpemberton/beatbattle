import Vue from 'vue'
import Card from './Card'
import Child from './Child'
import Button from './Button'
import Checkbox from './Checkbox'

import SampleUpload from './SampleUpload'
import CreateBattle from './CreateBattle'
import CreateEntry from './CreateEntry'
import EditBattle from './EditBattle'
import EditEntry from './EditEntry'
import BattleList from './BattleList'
import EntryList from './EntryList'
import BattleCard from './BattleCard'
import BattleCountdown from './BattleCountdown'
import VoteButton from './VoteButton'
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
  CreateEntry,
  EditBattle,
  EditEntry,
  BattleList,
  EntryList,
  BattleCard,
  BattleCountdown,
  VoteButton

].forEach(Component => {
  Vue.component(Component.name, Component)
})
