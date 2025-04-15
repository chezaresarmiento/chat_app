<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <div>
              <h1>Available Chat Rooms</h1>
              <ul>
                <li v-for="room in rooms" :key="room.id">
                  <button @click="joinRoom(room)">
                    {{ room.name }}
                  </button>
                </li>
              </ul>

              <div v-if="activeRoom">
                <br />
                <div class="h2">Welcome to {{ activeRoom.name }}</div>
                <br />
                <div class="chat-window">
                  <div v-for="(msg, index) in messages" :key="index" class="chat-message">
                    <strong>
                      <span v-if="msg?.created_at"> [{{ msg.created_at }}] </span>
                      <span class="text-red-500">{{ msg?.name ?? "Unknown" }}</span>
                      :</strong
                    >
                    {{ msg?.content ?? "[No Content]" }}
                  </div>
                </div>
                <form @submit.prevent="sendMessage">
                  <input
                    type="text"
                    v-model="newMessage"
                    placeholder="Type your message..."
                  />
                  <button type="submit">Send</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import axios from "axios";
import "../echo";

export default {
  data() {
    return {
      // List of available rooms; these correspond to channel names.
      rooms: [
        { id: 1, name: "Room 1", channel: "room.1" },
        { id: 2, name: "Room 2", channel: "room.2" },
        { id: 3, name: "Room 3", channel: "room.3" },
        { id: 4, name: "Room 4", channel: "room.4" },
        { id: 5, name: "Room 5", channel: "room.5" },
      ],
      activeRoom: null,
      messages: [],
      newMessage: "",
      roomChannel: null,
    };
  },
  methods: {
    joinRoom(room) {
      console.log("Joining room:", room.channel);
      // Unsubscribe from the previous channel if exists.
      if (this.roomChannel) {
        window.Echo.leave(this.activeRoom.channel);
      }
      this.activeRoom = room;
      this.messages = [];

      // Subscribe to the new room's channel.
      this.roomChannel = window.Echo.channel(room.channel).listen(".MessageSent", (e) => {
        this.messages.push(e);
      });

      this.getMessages(room.channel);
    },
    sendMessage() {
      if (!this.activeRoom || !this.newMessage.trim()) return;

      // Send the message to your /messages endpoint along with the room identifier.
      axios
        .post("/messages", {
          content: this.newMessage,
          room: this.activeRoom.channel,
        })
        .then((response) => {
          // Optionally clear the message input after sending.
          this.newMessage = "";
        })
        .catch((error) => {
          console.error("Error sending message:", error);
        });
    },
    getMessages(room) {
      // Fetch messages for the room from your backend.
      axios
        .get(`/getMessages/${room}`)
        .then((response) => {
          //log("Fetched messages:", response.data);
          this.messages = response.data;
        })
        .catch((error) => {
          console.error("Error fetching messages:", error);
        });
    },
  },
  mounted() {},
};
</script>

<style>
.chat-window {
  border: 1px solid #ccc;
  height: 300px;
  overflow-y: scroll;
  padding: 10px;
  margin-bottom: 10px;
}
.chat-message {
  margin-bottom: 5px;
}
.h2 {
  font-size: 1.5rem;
  font-weight: bold;
}
</style>
