<div id="slim_t2">
  <!-- Informational Messages -->
  <h5 class="rightsidebar-right-heading text-uppercase text-xs" style="width: 100%;float: left;margin-top: 70px;clear: both;">
      <i class="menu-icon fa fa-comments"></i>
      Comments
  </h5>
  
  <div class="notification-container" v-if=" ! loadingNotifications && activeNotifications.length == 0"  style="width:100%;float: left;">
    @foreach ($comment as $item)
    <div class="alert alert-warning m-b-none" style="word-break: break-all;">
      @if ($item->comment != '')
          {{ $item->comment}}
      @elseif($item->attatchment != '')
          {{ $item->attatchment}}
      @endif
    </div>
    @endforeach
    </div>
</div>
