<template>
  <div class="games-detail">
    <div class="game-option row">
      <div class="col">
        Đặt cược <img src="images/mat_sau_anh_mini.png" width="30px" />
        x
        <input
          class="num-img"
          type="text"
          min="1"
          v-model="num_img"
          @change="changeConfigNum"
        />
        <button type="button" class="btn btn-success">
          <i class="fa fa-play" style="margin-right: 6px"></i> Sẵn sàng
        </button>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-lg-8">demo 1</div>
      <div class="col-12 col-lg-4">
        <div class="d-flex flex-stack py-4">
          <!--begin::Details-->
          <div class="d-flex align-items-center">
            <!--begin::Avatar-->
            <div class="symbol symbol-45px symbol-circle">
              <span
                class="symbol-label bg-light-danger text-danger fs-6 fw-bolder"
                >M</span
              >
            </div>
            <div class="ms-5">
              <a
                href="#"
                class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2"
                >Melody Macy</a
              >
              <div class="fw-semibold text-muted">melody@altbox.com</div>
            </div>
          </div>
          <div class="d-flex flex-column align-items-end ms-2">
            <span class="text-muted fs-7 mb-1">5 hrs</span>
          </div>
        </div>
      </div>
    </div>
    <div class="game-box-chat">
      <div class="card" id="kt_chat_messenger">
        <div class="card-header" id="kt_chat_messenger_header">
          <div class="card-title">
            <div class="symbol-group symbol-hover">
              <div class="symbol symbol-35px symbol-circle" v-for="(user, index) in list_user" :key="index">
                <img alt="Pic" :src="user.avatar"/>
              </div>
            </div>
          </div>
        </div>
        <div class="scroll-y card-body" id="kt_chat_messenger_body">
          <div class="me-n5 pe-5 h-300px h-lg-auto">
            <div   v-for="(item, index) in chatbox.list_message" :key="index">
              <div class="d-flex justify-content-start mb-10"  v-if="item.user_id != user.user_id">
                <div class="d-flex flex-column align-items-start">
                  <div class="d-flex align-items-center mb-2">
                    <div class="symbol symbol-35px symbol-circle">
                      <img alt="Pic" :src="item.avatar" />
                    </div>
                    <div class="ms-3">
                      <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">{{item.user_name}}</a>
                      <span class="text-muted fs-7 mb-1">{{item.created_at}}</span>
                    </div>
                  </div>
                  <div class="p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start">
                    {{item.content}}
                  </div>
                </div>
              </div>
              <div class="d-flex justify-content-end mb-10" v-else>
                <div class="d-flex flex-column align-items-end">
                  <div class="d-flex align-items-center mb-2">
                    <div class="me-3">
                      <span class="text-muted fs-7 mb-1">{{item.created_at}}</span>
                      <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1" >{{item.user_name}}</a>
                    </div>
                    <div class="symbol symbol-35px symbol-circle">
                      <img alt="Pic" :src="item.avatar" />
                    </div>
                  </div>
                  <div class="p-5 rounded bg-light-primary text-dark fw-semibold mw-lg-400px text-end">
                    {{item.content}}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer pt-4" id="kt_chat_messenger_footer">
          <div class="d-flex flex-stack">
            <div class="input-group">
              <textarea class="form-control" aria-label="With textarea" v-model="chatbox.message"></textarea>
              <span class="input-group-text btn btn-primary" @click=sendMessage()>Gửi</span>
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
  data: () => {
    return {
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
      room_info: "",
      round_info: "",
      num_img: 1,
      chatbox: {
        message:'',
        list_message:[],
      },
      user :{
        user_id: u.session().user_info.user_id,
        avatar: u.session().user_info.avatar,
        user_name: u.session().user_info.name,
      },
      list_user:[]
    };
  },
  created() {
    u.g(`/api/rooms/detail/${this.$route.params.id}`)
      .then((response) => {
        this.room_info = response.data;
        this.$socket.emit("userJoinRoom", {
          action: 'joinRoom',
          room_id: this.room_info.room_id,
          user_id: this.user.user_id,
          user_name: this.user.user_name,
          avatar: this.user.avatar
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
    changeConfigNum() {
      const data = {
        action: "change_config_num",
        room_id: this.room_info.room_id,
        num_img: this.num_img,
      };
      this.pusDataRound(data);
    },
    pusDataRound(data) {
      this.$socket.emit("dataRoom", data);
    },
    sendMessage(){
        console.log(u.getCurrentTime)
      if(this.chatbox.message && u.session()){
        const data_mess = {
          user_name : this.user.name,
          user_id : this.user.id,
          room_id: this.room_info.room_id,
          avatar : this.user.avatar,
          content : this.chatbox.message,
          created_at : u.getCurrentTime()
        }
        this.$socket.emit("messageChat", data_mess);
        this.chatbox.message = '';
      }
    }
  },
  sockets: {
    dataRoom: function (data) {
      if(data.action==='joinRoom'){
        const check_in_list = false
        for (let i = 0; i < this.list_user.length; i++) {
          if(this.list_user[i].user_id==data.user_id){
            this.list_user[i]= data;
            check_in_list = true
          }
        }
        if(!check_in_list){
          this.list_user.push(data)
        }
      }
    },
    messageChat: function (data) {
      this.chatbox.list_message.push(data)
    },
  },
};
</script>
<style scoped>

</style>
