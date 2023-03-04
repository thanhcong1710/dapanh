<template>
  <div class="games-detail">
    <div class="row">
      <div class="col">
        Đặt cược <input class="num-img" type="number" min="1" /> 
        <span><font-awesome-icon icon="fa-solid fa-hand" /></span>
      </div>
      <div class="col">Column</div>
    </div>
  </div>
</template>

<script>
import u from "../../utilities/utility";
import loader from "../../components/Loading";

export default {
  name: "Games",
  data: () => {
    return {
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
      room_info: "",
      round_info: "",
      num_img: "",
    };
  },
  created() {
    u.g(`/api/rooms/detail/${this.$route.params.id}`)
      .then((response) => {
        this.room_info = response.data;
        this.$socket.emit("userJoinRoom", {
          room_id: this.room_info.room_id,
        });
      })
      .catch((e) => {
        u.processAuthen(e);
      });
  },
  methods: {
    approveGame() {
      const data = {
        room_id: this.room_info.room_id,
        num_img: this.num_img,
        is_only: this.is_only,
      };
      u.p(`/api/rounds/approve`, data)
        .then((response) => {
          if (response.status == 1) {
          }
        })
        .catch((e) => {
          u.processAuthen(e);
        });
    },
    endGame() {
      const data = {
        room_id: this.room_info.room_id,
        num_img: this.num_img,
      };
      u.p(`/api/rounds/end`, data)
        .then((response) => {
          if (response.status == 1) {
          }
        })
        .catch((e) => {
          u.processAuthen(e);
        });
    },
  },
};
</script>

