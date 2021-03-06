import { ROLES } from '@/config'
import GroupIndex from '@/pages/Group/Index'
import GroupShow from '@/pages/Group/Show'

export default [
  {
    path: '/groups',
    name: 'GroupIndex',
    component: GroupIndex,
    meta: {
      roles: [ROLES.ADMIN, ROLES.CLIENT, ROLES.TEACHER]
    },
  },
  {
    path: '/groups/:id',
    name: 'GroupShow',
    component: GroupShow,
    meta: {
      roles: [ROLES.ADMIN, ROLES.TEACHER]
    },
  }
]
