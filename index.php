<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style type="text/css">
      [v-cloak] {
        display: none;
      }

      .product-card .card-img {
        cursor: pointer;
      }

    </style>
    <title>Sample Store</title>
  </head>
  <body>
    <div id="app" v-cloak>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Sample Store</a>
        <button type="button" class="btn btn-light ml-auto" data-toggle="modal" data-target="#cartModal">
          Cart <span class="badge badge-light">{{ totalItems }}</span>
        </button>
      </nav>

      <div class="container py-5">
        <div class="row">
          <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-2" v-for="product in products">
            <product-card
              :id="product.id"
              :name="product.name"
              :price="product.price"
              :description="product.description"
              :rate="product.rate"
              :sku="product.sku"
              :images="product.images"
              v-on:add-to-cart="addToCart"
            ></product-card>
          </div>
        </div>
      </div>

      <div class="modal fade" id="cartModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Cart</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <div class="mb-4" v-for="item in cart">
                  <p>{{ products[item.id].name }} <span class="text-muted">Php {{ products[item.id].price }}</span></p>
                  <div>
                    <div class="input-group">
                      <input class="form-control" type="number" min=0 v-model.number="item.amount">
                      <div class="input-group-append">
                        <span class="input-group-text">Php {{ item.amount * products[item.id].price }}</span>
                        <button class="btn btn-danger" type="button" @click.prevent="removeFromCart(item.id)">Remove</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              <p class="text-right">Php {{ totalPrice }}</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Proceed to Checkout</button>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/vue@2"></script> -->
    <script type="text/javascript">
      Vue.component('product-card', {
        props: [
          'id',
          'name',
          'price',
          'description',
          'rate',
          'sku',
          'images',
        ],
        data: function () {
          return {
            imageIndex: 0,
          }
        },
        methods: {
          onImageClick: function () {
            this.imageIndex += 1;
            this.imageIndex %= this.images.length;
          }
        },
        template: `
          <div class="card product-card">
            <img :src="images[imageIndex]" class="card-img card-img-top" @click="onImageClick">
            <div class="card-body">
              <a :href="'item.php?id='+id"><h5 class="card-title">{{ name }}</h5></a>
              <h6 class="card-subtitle mb-2 text-muted">Php {{ price }}</h6>
              <p class="card-text">{{ description }}</p>
              <p class="card-text">{{ rate }} stars</p>
              <button class="btn btn-primary" @click="$emit('add-to-cart', id)">Add to Cart</button>
            </div>
          </div>
        `
      });

      <?php

        $products_file = file_get_contents("./product.json");
        // $products = json_decode($products_file, true);

      ?>
      let products = <?php echo $products_file; ?>;
      var cart = localStorage.getItem('cart');
      if (cart !== null) {
        cart = JSON.parse(cart);
      } else {
       cart = {};
      }

      let app = new Vue({
        el: '#app',
        data: {
          products: products,
          cart: cart,
        },
        methods: {
          addToCart: function (id) {
            if (id in this.cart) {
              this.cart[id].amount += 1;
            } else {
              var item = {
                id: id,
                amount: 1,
              };
              this.$set(this.cart, id, item);
            }
          },
          removeFromCart: function(id) {
            this.$delete(this.cart, id);
          },
        },
        computed: {
          totalItems: function () {
            var totalItems = 0;
            for (let key in this.cart) {
              totalItems += this.cart[key].amount;
            }
            return totalItems;
          },
          totalPrice: function () {
            var totalPrice = 0;
            for (let key in this.cart) {
              totalPrice += (this.cart[key].amount * this.products[key].price);
            }
            return totalPrice;
          },
        },
        watch: {
          cart: {
            handler: function (val, oldVal) {
              localStorage.setItem('cart', JSON.stringify(val));
            },
            deep: true
          },
        },
      })
    </script>
  </body>
</html>