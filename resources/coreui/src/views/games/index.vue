<template>
  <div class="games-index">
    <loader :active="loading.processing" :text="loading.text" />
    <div
      class="col-12"
      v-for="(item, index) in list_game"
      :key="index"
      @click="createRoom(item.id)"
    >
      <div class="card mb-4">
        <div class="card-body">
          <div class="row">
            <div class="col-3">
              <img :src="item.thumb_nail" />
            </div>
            <div class="col-9">
              <h2>{{ item.title }}</h2>
              <p>{{ item.description }}</p>
              <button class="btn btn-light" type="button">CHƠI NGAY</button>
            </div>
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
      list_game: [],
    };
  },
  created() {
    u.g(`/api/games/list`)
      .then((response) => {
        this.list_game = response.data;
      })
      .catch((e) => {
        u.processAuthen(e);
      });
  },
  methods: {
    createRoom(game_id) {
      const data = {
        game_id: game_id,
      };
      this.loading.processing = true;
      u.p(`/api/rooms/create`, data)
        .then((response) => {
          this.loading.processing = false;
          if (response.data.status == 1) {
            if(response.data.type_game == 1){
              this.$router.push({ path: `/rooms/${response.data.room_code}/detail` });
            }else if((response.data.type_game == 2)){
              this.$router.push({ path: `/rooms/${response.data.room_code}/detail_2` });
            }
          }
        })
        .catch((e) => {
          u.processAuthen(e);
        });
    },
  },
};
</script>
