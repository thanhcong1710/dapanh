import u from '../../utilities/utility'
const ParentsList = () => import('../../views/games/index')

export default {
  router: {
    path: '/games',
    name: '',
    component: {
      render (c) {
        return c('router-view')
      }
    },
    children: [
      {
        path: '/games',
        name: 'Danh Sách Khách Hàng',
        component: ParentsList
      }
    ]
  }
}
