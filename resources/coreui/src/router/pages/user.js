import u from '../../utilities/utility'
const UserImages = () => import('../../views/user/images')

export default {
  router: {
    path: '/user',
    name: '',
    component: {
      render (c) {
        return c('router-view')
      }
    },
    children: [
      {
        path: '/user/images',
        name: 'Danh Sách Ảnh',
        component: UserImages
      },
    ]
  }
}
