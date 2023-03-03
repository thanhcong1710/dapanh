import u from '../../utilities/utility'
const GamesList = () => import('../../views/games/index')
const GamesDetail = () => import('../../views/games/detail')

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
        name: 'Danh Sách Game',
        component: GamesList
      },
      {
        path: '/parents/:id/detail',
        name: 'Chi Tiết Game',
        component: GamesDetail
      },
    ]
  }
}
