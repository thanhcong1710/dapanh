<template>
  <div class="user-list-image">
    <loader :active="loading.processing" :text="loading.text" />
    <div>
      <h2>Tổng số ảnh: {{images.total}}</h2>
    </div>
    <div>
      <div class="row">
          <div class="col-4 col-md-2" v-for="(item, index) in images.list" :key="index">
            <div class="box-item-img">
              <img :src="'images/items/'+item.img_key+'_mini-min.jpg'" width="100%" />
              <div style="font-size:18px; text-align:center">x {{ item.num}}</div>
            </div>
          </div>
        </div>
    </div>
  </div>
</template>

<script>
import u from "../../utilities/utility";
import loader from "../../components/Loading";

export default {
  name: "Games",
  components: {
    loader: loader,
  },
  data() {
    return {
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
      images: {
        list:[],
        total:0
      }
    };
  },
  created() {
    u.g(`/api/user/images`)
      .then((response) => {
        this.images.list = response.data.list;
        this.images.total = response.data.total;
      })
      .catch((e) => {
        u.processAuthen(e);
      });
  },
  methods: {
  },
};
</script>
