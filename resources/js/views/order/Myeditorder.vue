<template>
    <div>
        <div class="row">
            <div class="col-md-7">
                <div class="order">
                    <div class="categries mb-3">
                        <select
                            @change="setproducts(selectedCategory)"
                            v-model="selectedCategory"
                            class="custom-select form-select form-select-lg" aria-label=".form-select-lg example">
                            <option v-for="(item, index) in categories" :key="index" :value="item.id">
                                {{item.name}}
                            </option>
                        </select>
                    </div>

                    <div class="products">
                        <div class="row">
                            <div class="col-6 col-sm-3" 
                                v-for="(item, index) in selectedProducts" :key="index">
                                <myproductcard @add="addToOrderList(item.id)" :is_added="orderItem.includes(item.id)" :id="item.id" :name="item.name" :price="'$ ' + item.price" :img="item.img_path"></myproductcard>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="invoice">
                    <div class="clint">
                        <template v-if="clintInfo !== null">
                            <h6 v-if="clintInfo.name !== null" class="mr-3">name: <strong>{{clintInfo.name}}</strong></h6>
                            <h6 v-if="clintInfo.address !== null" class="mr-3">address: <strong>{{clintInfo.address}}</strong></h6>
                            <h6 v-if="clintInfo.phone !== null">phone: <strong>{{clintInfo.phone}}</strong></h6>
                        </template>
                    </div>
                    
                    <table v-if="invoiceItems.length !== 0" class="table mb-0">
                        <thead class="table-primary">
                            <tr>
                            <th scope="col">product name</th>
                            <th scope="col">quantity</th>
                            <th scope="col">price</th>
                            <th scope="col">action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in invoiceItems" :key="index">
                                <td>{{item.name}}</td>
                                <td><input @change="getTotal(index)" class="quantity" type="number" min="1" max="999" v-model="invoiceItems[index].quantity"></td>
                                <td>$ {{item.price}}</td>
                                <td>
                                    <button @click="removeFromOrderList(item.product_id)" class="btn btn-sm btn-danger"><i class="fa fa-trash-alt"></i></button>
                                </td>
                            </tr>
                            <tr v-if="discount > 0" class="table-danger">
                                <td>discount</td>
                                <td>---</td>
                                <td>$ {{discount}}</td>
                                <td>
                                    <button @click="removeDiscount()" class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div  v-if="invoiceItems.length !== 0" class="payment">
                        <div v-if="newDataErrors.total" class="text-danger">
                                    {{newDataErrors.total [0]}}
                        </div>
                        <div class="row">    
                            <div class="col-4">total</div>
                            <div class="col-4">$  {{total}}</div>
                            <div class="col-4">
                                 <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#discount" title="discount"><i class="fas fa-dollar-sign"></i></button>
                                 <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#note" title="note"><i class="fas fa-clipboard"></i></button>
                            </div>
                        </div>
                    </div>  
                    <div v-if="newDataErrors.order_items" class="text-danger myerror">
                            order must be one item at least
                    </div>
                    <div v-if="note !== null && note.length > 0" class="note">
                        note: <strong>{{note}}</strong>
                    </div>
                    <div class="row"  v-if="invoiceItems.length !== 0 && clintInfo !== null">
                        <div class="col-12">
                            <button @click="updateOrder()" class="btn btn-block btn-success mb-3">save change</button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-block btn-primary">print</button>
                        </div>
                        <div class="col-6">
                            <button @click="clear()" class="btn btn-block btn-warning">clear</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="all_modals">
            <!-- discount Modal -->
            <div class="modal fade" id="discount" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">order discount</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <input
                                    :class='[ discountError !== null ? "is-invalid" : ""]'
                                    v-model="discount"  type="number" class="form-control" 
                                    placeholder="discount">
                                <div class="invalid-feedback" v-if="discountError !== null">{{ discountError }}</div>
                            </div> 
                        </div>
                        <div class="modal-footer">
                            <button @click="removeDiscount()" class="btn btn-secondary">cancel discount</button>
                            <button @click="getDiscount()" class="btn btn-success">add discount</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- note Modal -->
            <div class="modal fade" id="note" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">order note</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <textarea v-model="note"  class="form-control" placeholder="note ..."></textarea>
                            </div> 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-dismiss="modal">done</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</template>

<script>
import Myproductcard from '../../components/__Myproductcard.vue'

export default {
    data(){
        return {
            clintInfo: null,

            newDataErrors: {},

            categories: [],
            selectedCategory: null,
            selectedProducts: null,

            orderItem: [],
            invoiceItems: [],
            total: 0,
            discount: null,
            discountError: null,
            note: null,

        }
    },
    components:{
        Myproductcard
    },
    methods:{
        read: function(){

            const selectedCategory = this.selectedCategory != null ?  this.selectedCategory : null

            axios({
                    url: '/categories',
                    method: 'get',
                    baseURL: 'http://127.0.0.1:8000/api/',
                    params: { 
                        selectedCategory
                    }
            })
            .then(response=> {
                this.categories = response.data.data
                this.selectedCategory = response.data.data[0].id
                this.setproducts(response.data.data[0].id)
            })
            .catch( error => {
               //console.log(error)
            });
            
        },
        getOrder: function(id){

            axios({
                    url: '/order/' + id,
                    method: 'get',
                    baseURL: 'http://127.0.0.1:8000/api/',
            })
            .then(response=> {
                this.clintInfo = response.data.data.clint
                this.invoiceItems =  response.data.data.items
                for (let i = 0; i < response.data.data.items.length; i++) {
                    var element = response.data.data.items[i]
                    this.orderItem.push(element.product_id) 
                }
                this.total = response.data.data.total
                this.discount = response.data.data.discount
                this.note = response.data.data.note

            })
            .catch( error => {
               //console.log(error)
            });
        },
        setproducts: function(id){
            for (var i=0 ; i < this.categories.length ; i++)
            {
                if(id == this.categories[i].id){
                    this.selectedProducts = this.categories[i].products
                } 
            }
        },
        clear(){
            this.clintInfo = null
            this.orderItem = []
            this.invoiceItems = []
            this.total = 0
            this.discount = null
        },
        addToOrderList: function(id){
            this.orderItem.push(id)
            for(var i=0 ; i < this.selectedProducts.length ; i++){
                if(this.selectedProducts[i].id == id){
                    this.invoiceItems.push({
                        product_id: this.selectedProducts[i].id,
                        name: this.selectedProducts[i].name,
                        quantity: 1,
                        itemPrice: this.selectedProducts[i].price,
                        price: this.selectedProducts[i].price,
                    })
                    this.total = this.total +  this.selectedProducts[i].price                    
                }
            }
        },
        removeFromOrderList: function(id){
            var index = this.orderItem.indexOf(id);
            this.total = this.total - this.invoiceItems[index].price
            this.total = parseFloat( this.total.toFixed(2) )

            this.orderItem.splice(index, 1);
            this.invoiceItems.splice(index, 1);

            if(this.total <= 0){

                this.discount = null
                this.total = 0
                for (let i = 0; i < this.invoiceItems.length; i++) {
                    this.total = this.total + this.invoiceItems[i].price;
                }
                this.total = this.total - this.discount
                this.total = parseFloat( this.total.toFixed(2) )
                
            }

        },
        getTotal:function(index){

            this.invoiceItems[index].price = this.invoiceItems[index].itemPrice * this.invoiceItems[index].quantity
            
            this.total = 0
            for (let i = 0; i < this.invoiceItems.length; i++) {

                this.total = this.total + this.invoiceItems[i].price;

            }
            this.total = this.total - this.discount
            this.total = parseFloat( this.total.toFixed(2) )

            

        },
        getDiscount:function(){
            if(this.discount < 0){
                this.discountError = "discount must be a number and greater than zero"
            }
            if(this.discount > this.total){
                this.discountError = "The discount must be a number less than order total"
            }
            if(this.discount > 0 && this.discount < this.total){
                this.total = 0
                for (let i = 0; i < this.invoiceItems.length; i++) {

                    this.total = this.total + this.invoiceItems[i].price;

                }
                this.total = this.total - this.discount
                this.total = parseFloat( this.total.toFixed(2) )

                this.discountError = null

                document.querySelectorAll('[data-dismiss="modal"]').forEach(element => element.click())
            }
        },
        removeDiscount:function(){
            this.discount = null
            this.total = 0
            for (let i = 0; i < this.invoiceItems.length; i++) {

                this.total = this.total + this.invoiceItems[i].price;

            }
            this.total = parseFloat( this.total.toFixed(2) )
            document.querySelectorAll('[data-dismiss="modal"]').forEach(element => element.click())
        },
        updateOrder: function(){

            var fd = new FormData()
            fd.append("_method", "put");

            fd.append('total', this.total)
            fd.append('discount', this.discount)
            fd.append('note', this.note)

            fd.append('order_items[]', JSON.stringify(this.invoiceItems))

            axios({
                    url: '/order/' + this.$route.params.id,
                    method: 'post',
                    baseURL: 'http://127.0.0.1:8000/api/',
                    data: fd,
            })
            .then(response=> {
                if(response.data.status == "error"){
                    this.newDataErrors = response.data.errors
                } else if(response.data.status == "success") {
                    console.log(response.data.status)
                     this.read()
                    Toast.fire({
                        icon: 'success',
                        title: response.data.message
                    })
                    this.newDataErrors = {}
                    document.querySelectorAll('[data-dismiss="modal"]').forEach(element => element.click())
                    
                }
               
            })
            .catch( error => {
               //console.log(error)
            });
        }
    },
    mounted(){
        this.getOrder(this.$route.params.id)
        this.read();
    }
}
</script>

<style>
.myerror{
    padding: 10px;
}
.categries{
    padding: 10px;
    background-color: #b8daff;;
}
.clint{
    display: flex;
    flex-wrap: wrap;
    padding: 13px;
    margin-bottom: 10px;
    background-color: #b8daff;;
}
.clint h6{
    margin: 6px 0;
}
.quantity{
    width: 60px;
}
.payment{
    padding: 0 20px;
    background-color: #b8daff;;
    border-top: 1px solid #7abaff;
    margin-bottom: 10px;
}
.note{
    padding: 10px 20px;
    background-color: #b8daff;;
    margin-bottom: 10px;
}
.payment .row div{
    padding: 10px 0;
}
.clint_item{
    text-align: center;
    padding: 10px;
    box-shadow: 0 1px 5px 2px rgba(0, 0, 0, 0.5);
}
</style>