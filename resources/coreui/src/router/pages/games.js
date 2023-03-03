import u from '../../utilities/utility'
const GamesList = () => import('../../views/games/index')
const GamesDetail = () => import('../../views/games/detail')
const GamesDetail_2 = () => import('../../views/games/detail_2')
const GamesDetail_3 = () => import('../../views/games/detail_3')

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
        path: '/games/:id/detail',
        name: 'Chi Tiết Game',
        component: GamesDetail
      },
      {
        path: '/games/:id/detail_2',
        name: 'Chi Tiết Game',
        component: GamesDetail_2
      },
      {
        path: '/games/:id/detail_3',
        name: 'Chi Tiết Game',
        component: GamesDetail_3
      },
    ]
  }
}
