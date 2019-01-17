import TestIndex from '@/pages/Test/Index'
import TestClientStart from '@/pages/Test/Client/Start'
import { ROLES } from '@/config'
// import TestShow from '@/pages/Test/Show'
// import TestForm from '@/pages/Test/Form'

export default [
  {
    path: '/tests',
    name: 'TestIndex',
    component: TestIndex,
    meta: {
      roles: [ROLES.ADMIN, ROLES.CLIENT]
    }
  },
  {
    path: '/tests/:id',
    name: 'TestClientStart',
    component: TestClientStart,
    meta: {
      roles: [ROLES.CLIENT],
    }
  },
  // {
  //   path: '/tests/:id/edit',
  //   name: 'TestEdit',
  //   component: TestForm
  // },
  // {
  //   path: '/tests/create',
  //   name: 'TestCreate',
  //   component: TestForm
  // }
]