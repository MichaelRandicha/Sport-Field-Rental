<div class="horizontal-menu">
    <nav>
        <ul id="nav_menu">
            <li @if(Request::is('lapangan') || Request::is('lapangan/*')) class="active" @endif>
                <a href="{{ route('lapangan.index') }}"><i class="ti-basketball"></i><span>Lapangan</span></a>
                @if(Auth::user()->isPL())
                    <ul class="submenu">
                        <li><a href="{{ route('lapangan.create') }}">Buat Baru</a></li>
                    </ul>
                @endif
            </li>
            @if(Auth::user()->isPO())
                <li @if(Request::is('pembayaran') || Request::is('pembayaran/*')) class="active" @endif>
                    <a href="{{ route('PO.pembayaran.index') }}"><i class="ti-money"></i><span>Pembayaran</span></a>
                </li>
            @elseif(Auth::user()->isPL())
                <li @if(Request::is('manage/CS')) class="active" @endif>
                    <a href="{{ route('CS.index') }}"><i class="ti-user"></i><span>Kelola Customer Service</span></a>
                    <ul class="submenu">
                        <li><a href="{{ route('CS.create') }}">Buat Baru</a></li>
                    </ul>
                </li>
            @elseif(Auth::user()->isCS())
            
            @endif
            {{-- <li>
                <a href="javascript:void(0)"><i class="fa fa-table"></i>
                <span>Tables</span></a>
                <ul class="submenu">
                    <li><a href="table-basic.html">basic table</a></li>
                    <li><a href="table-layout.html">table layout</a></li>
                    <li><a href="datatable.html">datatable</a></li>
                </ul>
            </li> --}}
            {{-- <li><a href="maps.html"><i class="ti-map-alt"></i> <span>maps</span></a></li> --}}
            </ul>
        </nav>
    </div>
</div>