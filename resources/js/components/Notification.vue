<template>
    <li class="dropdown">
        <i class="ti-bell dropdown-toggle" data-toggle="dropdown" id="notification">
            @if(Auth::user()->unreadNotifications()->count() > 0)
                <span id="notification-count">{{ unreads.length }}</span>
            @endif
        </i>

        <div class="dropdown-menu bell-notify-box notify-box">
            <span class="notify-title" id="notification-title">You have {{ unreads.length }} new notifications</span>
            <div class="nofity-list">
                @forelse(Auth::user()->notifications as $notification)
                    @if(class_basename($notification->type) == "NewOrder")
                        @include('user.notification.NewOrder', ['notification' => $notification])
                    @elseif(class_basename($notification->type) == "OrderAccepted")
                        @include('user.notification.OrderAccepted', ['notification' => $notification])
                    @elseif(class_basename($notification->type) == "OrderDenied")
                        @include('user.notification.OrderDenied', ['notification' => $notification])
                    @elseif(class_basename($notification->type) == "OrderCanceled")
                        @include('user.notification.OrderCanceled', ['notification' => $notification])
                    @elseif(class_basename($notification->type) == "OrderTLE")
                        @include('user.notification.OrderTLE', ['notification' => $notification])
                    @elseif(class_basename($notification->type) == "NewReview")
                        @include('user.notification.NewReview', ['notification' => $notification])
                    @elseif(class_basename($notification->type) == "ReviewReply")
                        @include('user.notification.ReviewReply', ['notification' => $notification])
                    @elseif(class_basename($notification->type) == "OrderAlmostEndCS")
                        @include('user.notification.OrderAlmostEndCS', ['notification' => $notification])
                    @elseif(class_basename($notification->type) == "OrderAlmostEndPO")
                        @include('user.notification.OrderAlmostEndPO', ['notification' => $notification])
                    @elseif(class_basename($notification->type) == "OrderAlmostStartCS")
                        @include('user.notification.OrderAlmostStartCS', ['notification' => $notification])
                    @elseif(class_basename($notification->type) == "OrderAlmostStartPO")
                        @include('user.notification.OrderAlmostStartPO', ['notification' => $notification])
                    @elseif(class_basename($notification->type) == "OrderEndCS")
                        @include('user.notification.OrderEndCS', ['notification' => $notification])
                    @elseif(class_basename($notification->type) == "OrderEndPO")
                        @include('user.notification.OrderEndPO', ['notification' => $notification])
                    @elseif(class_basename($notification->type) == "OrderStartCS")
                        @include('user.notification.OrderStartCS', ['notification' => $notification])
                    @elseif(class_basename($notification->type) == "OrderStartPO")
                        @include('user.notification.OrderStartPO', ['notification' => $notification])    
                    @endif
                @empty
                <a href="#" class="notify-item">
                    <div class="notify-text">
                        <p>Notification is Empty</p>
                    </div>
                </a>
                @endforelse
            </div>
        </div>
    </li>
</template>

<script>
    export default {
        props:['unreads'],
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
