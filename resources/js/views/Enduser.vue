<template>
  <div class="enduser">
        <div class="overlay">


            <div class="box">
                <h2 class="box-title">order track</h2>
                <div class="mb-3">
                    <input v-model="id" type="text" class="form-control form-control-lg" placeholder="order id">
                </div>
                <div class="mb-3">
                    <button @click="getOrder" class="btn btn-lg btn-block btn-primary">get order</button>
                </div>
                <h2 v-if="order == null && error != null" class="text-danger">{{error}}</h2>
                <div v-if="order != null && order_status.includes(order.status)" class="order">
                    <h2 class="order-title">order status 
                        <span v-if="order.status == 'pending'" class="text-warning">pending</span>
                        <span v-if="order.status == 'canceled'" class="text-secondary">canceled</span>
                        <span v-if="order.status == 'complete'" class="text-success">complete</span>       
                        <span v-if="order.status == 'refound'" class="text-danger">refound</span>
                        <span v-if="order.status == 'shipping'" class="text-primary">shipping</span>
                        <span v-if="order.status == 'processing'" class="text-info">processing</span>
                    </h2>
                    <button  class="btn btn-lg btn-block btn-info" data-toggle="modal" data-target="#show_modal">show more details</button>
                </div>
            </div>


        </div>
        <div class="all_modals" v-if="order != null && order_status.includes(order.status)">
            <!-- show Modal -->
            <div class="modal fade" id="show_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">order details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="clint" v-if="order.items">
                            <h6 v-if="order.clint.name !== null" class="mr-3">
                                name: <strong>{{order.clint.name }}</strong>
                            </h6>
                            <h6 v-if="order.clint.address !== null" class="mr-3">
                                address: <strong>{{order.clint.address}}</strong>
                            </h6>
                            <h6 v-if="order.clint.phone !== null">
                                phone: <strong>{{order.clint.phone}}</strong>
                            </h6>
                        </div>

                        <table v-if="order.items && order.items.length !== 0" class="table mb-0">
                            <thead class="table-primary">
                                <tr>
                                <th scope="col">product name</th>
                                <th scope="col">quantity</th>
                                <th scope="col">price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in order.items" :key="index">
                                    <td>{{item.name}}</td>
                                    <td>{{item.quantity}}</td>
                                    <td>$ {{item.price}}</td>
                                </tr>
                                <tr v-if="order.discount > 0" class="table-danger">
                                    <td>discount</td>
                                    <td>---</td>
                                    <td>$ {{order.discount}}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div  v-if="order.length !== 0" class="payment">
                            <div class="row">    
                                <div class="col-6">total</div>
                                <div class="col-6">$  {{order.total}}</div>
                            </div>
                        </div>
                        <div v-if="order.note !== null" class="note">
                            note: <strong>{{order.note}}</strong>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
  </div>
</template>

<script>
export default {
    name: 'OrderTrack',
    data(){
        return {
            id: null,
            order: null,
            error: null,
            order_status:[
                'pending',
                'canceled',
                'complete',
                'refound',
                'shipping',
                'processing',
            ]
        }
    },
    methods:{
        getOrder(){
            axios({
                    url: `/getorder/${this.id}`,
                    method: 'get',
                    baseURL: 'http://127.0.0.1:8000/api/',
            })
            .then(response=> {
                if(response.data.status == 'success'){
                    this.order = response.data.data
                    this.error= null
                }
                if(response.data.status == 'error'){
                    this.error = response.data.message
                    this.order= null
                }
                            
            })
            .catch( error => {
               //console.log(error)
            });
        }
    }
}
</script>

<style>
    .enduser{
        width: 100%;
        height: 100vh;
        background-image: url("../assets/background.jpg");
        background-size: cover;
        position: relative;
    }
    .enduser .overlay{
        background-color: #000000b5;
        width: 100%;
        height: 100%;
        padding: 15px;
    }
    .enduser .box{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        max-width: 500px;
        height: 400px;
    }
    .enduser .box .box-title{
        text-align: center;
        color: #fff;
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 30px;
    }
    .enduser .order .order-title{
        text-align: center;
        color: #fff;
        font-size: 24px;
        margin-bottom: 20px;
        font-weight: bold;
    }
</style>