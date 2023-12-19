<template>
    <div>
        <heading class="mb-6">{{order_type == 'sales-orders' ? __('Sales Order') : __('Purchase Order')}}</heading>
        
        <card class="flex flex-col mb-8 items-center justify-left" style="min-height: 300px">
            <div class="flex border-b border-40 w-full">
                <div class="px-8 py-2 w-1/5">
                    <label for="customer" class="inline-block text-80 pt-2 leading-tight">{{__('Store')}}&nbsp;<!----></label>
                </div> 
                <div class="py-2 px-8 w-1/2">{{user_info.store_name}}</div>
            </div>
            <div class="flex border-b border-40 w-full">
                <div class="px-8 py-2 w-1/5">
                    <label for="customer" class="inline-block text-80 pt-2 leading-tight">{{__('Form Maker')}}&nbsp;<!----></label>
                </div> 
                <div class="py-2 px-8 w-1/2">{{user_info.name}}{{user_info.mobile}}</div>
            </div>
            <span class="text text-danger">{{error}}</span>
            <div class="flex border-b border-40 w-full" v-if="order_type == 'sales-orders'">
                <div class="px-8 py-2 w-1/5">
                    <label for="customer" class="inline-block text-80 pt-2 leading-tight">{{__('Customer')}}&nbsp;<!----></label>
                </div> 
                <div class="py-2 px-8 w-1/2">
                    <v-select class="" name="" v-model="order.customer_id" v-if="customers" :options="customers" :reduce="item => item.id" label="name">
                    </v-select>
                </div>
            </div>
            <div class="flex border-b border-40 w-full">
                <div class="px-8 py-2 w-1/5">
                    <label for="customer" class="inline-block text-80 pt-2 leading-tight">{{__('Goods')}}&nbsp;<!----></label>
                </div> 
                <div class="py-2 px-8 w-4/5">
                    <vxe-table border resizable show-overflow show-footer 
                        class="w-full" 
                        ref="xTable"
                        :footer-method="footerMethod"
                        :data="order.items" 
                        :edit-config="{trigger: 'click', mode: 'row'}">
                        <vxe-column type="seq" :title="__('Index No')" width="10%">
                            <template #default="{ row }">
                                <button type="button" name="button" class='text-danger' @click="deleteItem(row.key)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current">
                                        <path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
                                    </svg>
                                </button>
                                <button type="button" name="button" class='text-success' @click="addItem(row.key)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>   
                            </template>  
                        </vxe-column>
                        
                        <vxe-column field="goods" :title="__('Goods')" :edit-render="{}" width="40%">
                            <template #default="{ row }">
                                <span>{{ goodsInfo(row.attributes.goods_id, 'name') }}</span>
                            </template>
                            <template #edit="{ row }">
                                <v-select class="" name="" v-model="row.attributes.goods_id" @input="updateFooterEvent" v-if="goods_options" :options="goods_options" :reduce="goods => goods.id" label="name">
                                </v-select>
                            </template>
                        </vxe-column>
                        <vxe-column field="role" :title="__('Image')" width="10%">
                            <template #default="{ row }"> 
                                <img :src="goodsInfo(row.attributes.goods_id, 'thumb')" />
                            </template>
                        </vxe-column>
                        <vxe-column field="num6" :title="__('Price')" width="10%">
                            <template #default="{ row }">
                                <span>¥{{price(row.attributes.goods_id)}}</span>
                            </template>
                        </vxe-column>
                        <vxe-column field="num6" :title="__('Quantity')" :edit-render="{immediate: true}" width="15%">
                            <template #default="{ row }">
                                <span>{{row.attributes.quantity}}</span>
                            </template>
                            <template #edit="{ row }">
                                <vxe-input v-model="row.attributes.quantity" type="number" @change="updateFooterEvent" min="1" placeholder="请输入数量"></vxe-input>
                            </template>
                        </vxe-column>
                        <vxe-column field="num6" :title="__('Subtotal')"  width="15%">
                            <template #default="{ row }">
                                <span>¥{{subtotal(row)}}</span>
                            </template>
                        </vxe-column>
                    </vxe-table>   
                </div>        
            </div>   

            <div class="flex border-b border-40 w-full" v-if="order_type == 'sales-orders'">    
                <div class="px-8 py-2 w-1/5">
                    <label for="paid_price" class="inline-block text-80 pt-2 leading-tight">{{__('Paid Price')}}&nbsp;<!----></label>
                </div> 
                <div class="py-2 px-8 w-1/2"><input v-model="order.paid_price" v-on:keyup.enter="submit" id="paid_price" dusk="paid_price" list="paid_price-list" type="text" :placeholder="__('Paid Price')" class="w-full form-control form-input form-input-bordered"></div>
            </div> 
            <div class="flex border-b border-40 w-full">
                <div class="px-8 py-2 w-1/5">
                    <label for="comment" class="inline-block text-80 pt-2 leading-tight">{{__('Comment')}}&nbsp;<!----></label>
                </div> 
                <div class="py-2 px-8 w-1/2"><textarea v-model="order.comment" rows="3" id="comment" dusk="comment" list="paid_price-list" type="text" :placeholder="__('Comment')" class="w-full form-control form-input form-input-bordered"/></div>
            </div> 
            
            <!-- <button type="button" name="button" class="btn btn-default" @click="cancel">{{__('Cancel')}}</button>     -->
            <!-- <button type="button" name="button" class="btn btn-default btn-primary" @click="submit">{{__('Submit')}}</button> -->
        </card>
        <div class="flex items-center">
            <div class="px-8 py-2 w-1/5">
                
            </div> 
            <div class="py-2 px-8 w-1/2 flex items-center">
                <a tabindex="0" @click="cancel" class="btn btn-link dim cursor-pointer text-80 ml-auto mr-6">{{__('Cancel')}}</a> 
                <button type="submit" @click="submit" class="btn btn-default btn-primary inline-flex items-center relative" dusk="update-button"><span class="">
                    {{(this.order.id ? __('Update') : __('New')) + __('Sales Order')}} </span> <!---->
                </button>
            </div>
        </div>   
    </div>
</template>

<script>
import vSelect from "vue-select";

export default {
    components: {"v-select": vSelect},
    data: function(){
        return {
            order: {total_quantity: 0, total_price:0, total_price_label: "", items:[]},
            goods_detail: null,
            goods_options: [],
            customers: [],
            user_info: {},
            order_type: null //order or purchase_order
        }
    },
    metaInfo() {
        return {
        }
    },
    methods: {
        initRow(){
            return {layout: "items", key: Math.random(), attributes: {quantity: 1}}
        },
        addItem(key){
            for (var i=0; i<this.order.items.length; i++) {
                if (this.order.items[i].key == key) {
                    this.order.items.splice(i+1,0,this.initRow())
                    return;
                }
            }
            this.order.items.splice(0,0, this.initRow())
        },
        deleteItem(key) {
            // find index
            for (var i=0; i<this.order.items.length; i++) {
                if (this.order.items[i].key == key) {
                    this.order.items.splice(i,1)
                    return;
                }
            }
        },
        goodsInfo(id, key) {
            if (!id) return null;
            return this.goods_detail && this.goods_detail[id] ? this.goods_detail[id][key] : null;
        },
        updateFooterEvent(){
            const $table = this.$refs.xTable
            $table.updateFooter()            
        },
        calculate(){
            this.axios.post('/ajax/'+this.order_type+'/calculate', {goods: this.order.items}).then((response) => {
                console.log(response.data)
                if (response.data.success) {
                    var data = response.data.data
                    this.$set(this.order, 'total_quantity', data.total_quantity)
                    this.$set(this.order, 'total_price', data.total_price)
                    this.$set(this.order, 'total_price_label', data.total_price_label)
                }
            })
        },
        price(goods_id) {
            var key = (this.order_type == 'sales-orders') ? 'price' : 'price_purchase'
            return this.goodsInfo(goods_id, key)
        },
        sumNum (list, field) {
          let count = 0
          list.forEach(item => {
              if (!item.attributes.goods_id)return;
              count += Number(item.attributes[field])
          })
          return count
        },
        subtotal (row) {
            if (!row.attributes.goods_id)return null;
            return (this.price(row.attributes.goods_id) * row.attributes.quantity).toFixed(2)
        },
        totalAmount (data) {
          let count = 0
          data.forEach(row => {
            count += Number(this.subtotal(row))
          })
          return count.toFixed(2)
        },        
        footerMethod ({ columns, data }) {
              return [
                columns.map((column, columnIndex) => {
                  if (columnIndex === 0) {
                    return '合计'
                  }
                  if (columnIndex === 4) {
                    // return `${this.order.total_quantity} 件`
                    return `${this.sumNum(data, 'quantity')} 件`
                } else if (columnIndex === 5) {
                    // return `${this.order.total_price_label} `
                    return `¥${this.totalAmount(data)}`
                  }
                  return '-'
                })
              ]
          },
        cancel(){
            if (this.order.id) {
                this.$router.push({path: '/resources/'+this.order_type+'/'+this.order.id})
            }else{
                this.$router.push({path: '/resources/'+this.order_type})
            }
        },
        submit(){
            this.axios.post('/ajax/'+this.order_type, this.order).then((response) => {
                console.log(response.data.data)
                if (response.data.success) { 
                    this.$router.push({path: '/resources/'+this.order_type+'/'+response.data.data.id})
                }
            })
        }
    },
    mounted() {
        //
        if (!this.$route.query.order_type){
            // return alert("no order_type select")
            this.error = "no order_type select"
        }
        this.order_type = this.$route.query.order_type
        if (this.$route.query.id) {
            this.axios.get('/ajax/'+this.order_type+'/'+this.$route.query.id).then((response) => {
                if (response.data.success) {
                    this.order = response.data.data
                    this.updateFooterEvent()
                }
            })
        }else{
            this.order.items.push(this.initRow())
        }
        this.axios.get('/ajax/customers').then((response) => {
            console.log(response.data.success)
            if (response.data.success) {
                this.customers = response.data.data
            }
        })
        this.axios.get('/ajax/goods').then((response) => {
            console.log(response.data.success)
            if (response.data.success) {
                this.goods_detail = response.data.data
            }
        })
        this.axios.get('/ajax/goods/options').then((response) => {
            if (response.data.success) {
                this.goods_options = response.data.data
            }
        })
        this.axios.get('/ajax/user/info').then((response) => {
            if (response.data.success) {
                this.user_info = response.data.data
            }
        })
        
    },
}
import 'vue-select/dist/vue-select.css';

</script>

<style>

</style>
