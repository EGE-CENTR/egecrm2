import store from '@/store'

export const API_URL = 'requests'

export const REQUEST_STATUSES = [
  {text: 'новые', value: 'new'},
  {text: 'ожидающиеся', value: 'awaiting'},
  {text: 'выполненные', value: 'finished'}
]

export const MODEL_DEFAULTS = {
  status: 'new',
  phones: [{phone: '', comment: ''}]
}

export const FILTERS = [
  {label: 'Статус', field: 'status', type: 'select', options: REQUEST_STATUSES},
  {label: 'Класс', field: 'grade', type: 'select', options: store.state.data.grades, valueField: 'id', textField: 'title'},
  {label: 'Ответственный', field: 'responsible_admin_id', type: 'select', options: store.state.data.admins, valueField: 'id', textField: 'name'},
  {label: 'Имя', field: 'name', type: 'text'}
]

export const RequestList = require('./List')
export const RequestDialog = require('./Dialog')
