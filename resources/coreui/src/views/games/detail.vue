<template>
  <div class="games-detail">
    <div class="row">
      <div class="col-12 col-lg-6 games-detail-show" style="text-align:center">
        <div>
          <i class="fa fa-rotate" style="position: absolute;top: 0px;left: 0px;font-size: 24px;" ></i>
          <div :class="'flip-box '+ (flip_box.active? 'active':'')">
            <div class="flip-box-inner">
              <div class="flip-box-front">
                <img src="images/mat_sau_anh_large.png" width="100%" >
              </div>
              <div class="flip-box-back">
                <img src="images/items/image_1_large-min.jpg" width="100%">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6">
        <div class="list-user">
          <div class="box-action" v-if="user.user_id == room_info.created_by">
            <img src="images/mat_sau_anh_mini.png" width="30px" style="margin-top:-8px"/>
            x
            <input
              class="num-img"
              type="text"
              min="1"
              v-model="num_img"
              @input="onlyNumbers"
              @change="changeConfigNum"
            />
            <button type="button" class="btn btn-success" style="float:right" :disabled="btn_endgame" @click="endGame()">
              <i class="fa fa-play" ></i> Bắt đầu
            </button>
          </div>
          <div class="box-action" v-else>
            <img src="images/mat_sau_anh_mini.png" width="30px" style="margin-top:-8px"/>
            x <span style="font-size: 20px; color: #fff;">{{num_img}}</span>
            <button type="button" class="btn btn-success" style="float:right" @click="changeUserReady(1)"  v-if="!user.user_ready">
              <i class="fa fa-play" ></i> Sẵn sàng
            </button>
            <button type="button" class="btn btn-warning" style="float:right; color:#fff" @click="changeUserReady(0)" v-else>
              <i class="fa fa-play" ></i> Hủy khóa
            </button>
          </div>
          <div class="d-flex flex-stack" v-for="(user, index) in list_user" :key="index">
            <div class="d-flex align-items-center">
              <div class="symbol symbol-45px symbol-circle">
                <img :src="user.avatar ? user.avatar : 'img/avatars/1.svg'" width="38px"/>
              </div>
              <div class="ms-3">
                <a class="text-dark-75 text-hover-primary font-weight-bold font-size-lg">{{user.user_name}}</a><br>
                <p>(<b>{{user.user_num_img}}</b> ảnh)</p>
              </div>
            </div>
            <div class="d-flex flex-column align-items-end">
              <i :class="'fa-solid fa-square-check '+(user.user_ready || user.user_id == room_info.created_by ?'active':'waiting')"></i>
            </div>
          </div>
          <div style="text-align:center">
            <button class="btn btn-info" type="button"> <i class="fa fa-link"></i> MỜI</button>
            <button class="btn btn-info" type="button" @click="toggleBoxChat"><i class="fa fa-comment"></i> CHAT</button>
            <button class="btn btn-info" type="button" @click="addBot" :disabled="add_bot==1"><i class="fa fa-robot"></i> CHƠI VỚI MÁY</button>
          </div>
          <div class="game-box-chat" v-if="show_box_chat">
            <div class="card" id="kt_chat_messenger">
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
              <div class="card-footer" >
                <div class="input-group">
                  <input class="form-control" v-model="chatbox.message" />
                  <span class="input-group-text btn btn-primary" @click=sendMessage()>Gửi</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
    <CModal
      :title="modal.title"
      :show.sync="modal.show"
      :color="modal.color"
      :closeOnBackdrop="modal.closeOnBackdrop"
      :size="modal.size"
    >
      <div>
        <div class="form-in-list">
          <p style="color:#333">{{modal.error_message}}</p>
        </div>
      </div>
      <template #header>
        <h5 class="modal-title">{{ modal.title }}</h5>
      </template>
      <template #footer>
       
        <CButton :color="'btn btn-secondary'" @click="modal.show=false" type="button"
          >Đóng</CButton
        >
      </template>
    </CModal>
  </div>
</template>

<script>
import u from "../../utilities/utility";
import loader from "../../components/Loading";

export default {
  name: "Games",
  data: () => {
    return {
      modal: {
        title: "CẬP NHÂT TRẠNG THÁI KHÁCH HÀNG",
        show: false,
        color: "info",
        closeOnBackdrop: true,
        size:"lg",
        error_message:""
      },
      flip_box:{
        active: true,
        status_interval:0,
        id_interval:''
      },
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
        user_num_img: 0,
        user_ready:0
      },
      list_user:[],
      show_box_chat: false,
      round_ready: 1,
      add_bot:0,
      btn_endgame: false,
    };
  },
  created() {
    u.g(`/api/rooms/detail/${this.$route.params.id}`)
      .then((response) => {
        this.room_info = response.data;
        this.user.user_num_img = this.room_info.user_num_img
        this.$socket.emit("userJoinRoom", {
          action: 'joinRoom',
          room_id: this.room_info.room_id,
          user_id: this.user.user_id,
          user_name: this.user.user_name,
          avatar: this.user.avatar,
          user_num_img: this.room_info.user_num_img,
          user_ready: this.user.user_ready
        });
        if(this.user.user_id == this.room_info.created_by){
          this.changeUserReady(1)
        }
      })
      .catch((e) => {
        u.processAuthen(e);
      });
  },
  methods: {
    changeUserReady(status) {
      this.user.user_ready = status
      const data = {
        user_id: this.user.user_id,
        room_id: this.room_info.room_id,
        num_img: this.num_img,
        is_only: this.is_only,
        user_ready: status,
      };
      u.p(`/api/rounds/approve`, data)
        .then((response) => {
          if (response.data.status == 1) {
          }
        })
        .catch((e) => {
          u.processAuthen(e);
        });
    },
    loadingGame(){
      this.flip_box.status_interval = 1;
      this.flip_box.id_interval = setInterval(this.flipImage, 500);
    },
    endLoadingGame(){
      this.flip_box.status_interval = 0;
    },
    flipImage(){
      this.flip_box.active = ! this.flip_box.active;
      if(!this.flip_box.status_interval){
        clearInterval(this.flip_box.id_interval)
      }
    },
    endGame() {
      if(this.list_user.length>1){
        const data = {
          room_id: this.room_info.room_id,
          num_img: this.num_img,
        };
        this.loadingGame();
        this.btn_endgame=true;
        u.p(`/api/rounds/end`, data)
          .then((response) => {
            if (response.status == 1) {
            }
          })
          .catch((e) => {
            u.processAuthen(e);
          });
      }else{
        this.modal.show=true
        this.modal.title= "THÔNG BÁO"
        this.modal.error_message= "Mời thêm bạn hoặc chọn chơi với máy để bắt đầu"
      }
    },
    onlyNumbers() {  
      this.num_img = this.num_img.replace(/[^0-9]/g, '');
    },
    changeConfigNum() {
      if(this.user.user_num_img< this.num_img){
        this.modal.show=true
        this.modal.title= "THÔNG BÁO"
        this.modal.error_message= "Bạn không đủ ảnh để đặt cược"
      }else{
        const data = {
          user_id: this.user.user_id,
          room_id: this.room_info.room_id,
          num_img: this.num_img,
        };
        u.p(`/api/rounds/changeConfigNum`, data)
          .then((response) => {
            if (response.data.status == 1) {
            }
          })
          .catch((e) => {
            u.processAuthen(e);
          });
      }
    },
    pusDataRound(data) {
      this.$socket.emit("dataRoom", data);
    },
    sendMessage(){
      if(this.chatbox.message && u.session()){
        const data_mess = {
          user_name : this.user.name,
          user_id : this.user.id,
          room_id: this.room_info.room_id,
          avatar : this.user.avatar,
          content : this.chatbox.message,
          created_at : u.getCurrentTimeChat()
        }
        this.$socket.emit("messageChat", data_mess);
        this.chatbox.message = '';
      }
    },
    toggleBoxChat(){
      this.show_box_chat = this.show_box_chat == true ? false :true;
    },
    addBot(){
      this.add_bot=1
      let user_bot = {
        user_id: 999999,
        avatar: 'img/avatars/2.svg',
        user_name: 'Người Máy',
        user_num_img: 0,
        user_ready:1
      }
      this.list_user.push(user_bot)
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
      } else if(data.action==='approveRound'){
        for (let i = 0; i < this.list_user.length; i++) {
          if(this.list_user[i].user_id==data.user_id){
            this.list_user[i].user_ready= data.user_ready;
          }
        }
      }else if(data.action==='changeConfigNum'){
        for (let i = 0; i < this.list_user.length; i++) {
          if(this.list_user[i].user_id != 999999){
            this.list_user[i].user_ready= 0;
          }
        }
        this.num_img = data.num_img
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
