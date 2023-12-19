import axios from 'axios'
import VueAxios from 'vue-axios'
import 'xe-utils'
import VXETable from 'vxe-table'
import 'vxe-table/lib/style.css'

Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      // name: 'sales-orders-create',  
      path: '/sales-orders',
      // params: { resourceName: 'sales-order' },
      component: require('./components/Tool'),
    },
  ])
  Vue.use(VueAxios, axios)
  Vue.use(VXETable)

  // this.$router.push({
  //   name: 'create',
  //   params: { resourceName: this.resourceName },
  // })
  // const registeredViews =  JSON.parse('{"create":{"route":"create","component":"Create","name":"order-create-view"},"detail":{"route":"detail","component":"Detail","name":"order-detail-view"},"edit":{"route":"edit","component":"Update","name":"order-edit-view"}}')
  // const registeredViews =  JSON.parse('{"create":{"route":"create","component":"Create","name":"order-create-view"},"lens":{"route":"lens","component":"Lens","name":"order-lens-view"},"edit":{"route":"edit","component":"Update","name":"order-edit-view"},"edit-attached":{"route":"edit-attached","component":"UpdateAttached","name":"order-edit-attached-view"},"attach":{"route":"attach","component":"Attach","name":"order-attach-view"}}')
  // Object.keys(registeredViews).forEach(function(key) {
      // Vue.component('sales-order-create-view', require('./components/Tool'))
  // })
})
