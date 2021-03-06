import store from '@/store'
import USER_TYPES from '@/other/user-types'

export const API_URL = 'payments'

export const ENUMS = {
  methods: [
    {title: 'карта', id: 'card'},
    {title: 'наличные', id: 'cash'},
    {title: 'счет', id: 'bill'},
    {title: 'карта онлайн', id: 'card_online'},
    {title: 'взаимозачет', id: 'mutual'}
  ],
  types: [
    {title: 'платеж', id: 'payment'},
    {title: 'возврат', id: 'return'}
  ],
  categories: [
    {title: 'обучение', id: 'study'},
    {title: 'профориентация', id: 'career_guidance'},
    {title: 'пробный ЕГЭ', id: 'ege_trial'}
  ]
}

export const FILTERS = [
  {label: 'Тип', field: 'type', type: 'multiple', options: ENUMS.types},
  {label: 'Метод', field: 'method', type: 'multiple', options: ENUMS.methods},
  {label: 'Год', field: 'year', type: 'multiple', options: store.state.data.years},
  {label: 'Категория', field: 'category', type: 'multiple', options: ENUMS.categories},
  {label: 'От кого', field: 'entity_type', type: 'multiple', options: [
    {id: USER_TYPES.teacher, title: 'преподаватель'},
    {id: USER_TYPES.client, title: 'клиент'},
  ]},
  {label: 'Статус', field: 'is_confirmed', type: 'multiple', options: [
    {id: 0, title: 'не подтвержден'},
    {id: 1, title: 'подтвержден'},
  ]},
  {label: 'Пользователь', field: 'created_email_id', type: 'admin', valueField: 'email_id'},
  {label: 'Дата', field: 'date_timestamp', type: 'interval'},
  {label: 'Сортировка', field: 'sort', type: 'sort', valueField: 'field', options: [
    {field: 'created_at', type: 'desc', title: 'по дате проводки'},
    {field: 'date', type: 'desc', title: 'по дате'},
  ]},
]

export const SORT = [
  {field: 'created_at', type: 'desc', title: 'по дате проводки', selected: true},
  {field: 'date', type: 'desc', title: 'по дате', selected: false},
]

export const MODEL_DEFAULTS = {}

export const PaymentDialog = require('./Dialog')
export const PaymentList = require('./List')
