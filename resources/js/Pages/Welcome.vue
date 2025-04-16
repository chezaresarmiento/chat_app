<script setup>
import { Link } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

defineProps({
  canLogin: {
    type: Boolean,
  },
  canRegister: {
    type: Boolean,
  },
  laravelVersion: {
    type: String,
    required: true,
  },
  phpVersion: {
    type: String,
    required: true,
  },
});

function handleImageError() {
  document.getElementById("screenshot-container")?.classList.add("!hidden");
  document.getElementById("docs-card")?.classList.add("!row-span-1");
  document.getElementById("docs-card-content")?.classList.add("!flex-row");
  document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
  <Head title="Welcome" />
  <div
    class="bg-white text-gray-800 dark:bg-black dark:text-white transition-all duration-300"
  >
    <div class="min-h-screen flex flex-col items-center justify-center px-6 py-10">
      <!-- Logo and Navigation -->
      <header class="w-full max-w-7xl flex justify-between items-center mb-12">
        <Link :href="route('dashboard')">
          <ApplicationLogo class="h-10 w-auto text-red-600" />
        </Link>

        <nav v-if="canLogin" class="flex gap-4">
          <Link
            v-if="$page.props.auth.user"
            :href="route('dashboard')"
            class="text-red-600 hover:underline font-medium"
          >
            Dashboard
          </Link>
          <template v-else>
            <Link :href="route('login')" class="text-red-600 hover:underline font-medium">
              Log in
            </Link>
            <Link
              v-if="canRegister"
              :href="route('register')"
              class="text-red-600 hover:underline font-medium"
            >
              Register
            </Link>
          </template>
        </nav>
      </header>

      <!-- Hero Message -->
      <section class="text-center max-w-3xl mb-12">
        <h1 class="text-5xl font-bold mb-4 text-black dark:text-white">
          Welcome to <span class="text-red-600">CHAT app</span>, built in Laravel
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-300">
          Laravel is a web application framework with expressive, elegant syntax. Here’s
          what it offers:
        </p>
      </section>

      <!-- Features Grid -->
      <section class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl">
        <Feature title="⚡ Routing with Inertia">
          Simple and expressive SPA routing with server-side reactivity.
        </Feature>

        <Feature title="🔐 Authentication & Authorization">
          Laravel Breeze + Sanctum for secure login, roles, and tokens.
        </Feature>

        <Feature title="🧠 Eloquent ORM">
          Elegant ActiveRecord implementation for databases.
        </Feature>

        <Feature title="🧩 Blade Templating">
          Clean and minimal templating with inheritance & control.
        </Feature>

        <Feature title="📡 Echo & Broadcasting">
          Real-time WebSocket support with Laravel Echo + Pusher.
        </Feature>

        <Feature title="📦 Ecosystem">
          Includes MySQL, Redis, Jobs, Queues, and MailJet integration.
        </Feature>
      </section>

      <!-- Personal Message -->
      <div
        class="bg-red-100 border border-red-300 text-red-800 rounded-xl px-6 py-6 max-w-4xl mt-16 shadow-md text-center"
      >
        <h2 class="text-2xl font-semibold mb-2">Dear future employer,</h2>
        <p class="text-lg leading-relaxed">
          I am a passionate developer who loves to create functional and technical
          solutions. I’m eager to learn and grow in my career. I’m excited to work with
          you and contribute to your team.
          <strong>Let’s build something amazing together!</strong> <br />
          Please login or register to see the app in action.
        </p>
      </div>

      <!-- Footer -->
      <footer class="mt-16 text-sm text-gray-400 text-center">
        Built by Cesar Sarmiento · Laravel v{{ laravelVersion }} · PHP v{{ phpVersion }}
      </footer>
    </div>
  </div>
</template>
