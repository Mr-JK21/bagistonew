<div class="fixed h-full w-[260px] border-r transition-all duration-300 group-[.sidebar-collapsed]/container:w-[70px] max-lg:hidden z-[1]">
    <div class="journal-scroll h-[calc(100vh-114px)] overflow-x-hidden group-[.sidebar-collapsed]/container:overflow-y-auto">
        <nav class="grid w-full gap-2">
            <!-- Navigation Menu -->
            @foreach (marketplace_menu()->getItems('seller') as $menuItem)
                <div
                    class="group/item {{ $menuItem->isActive() ? 'active' : 'inactive' }}"
                    onmouseenter="adjustSubMenuPosition(event)"
                >
                    <a
                        href="{{ $menuItem->getUrl() }}"
                        class="flex cursor-pointer items-center justify-between p-5 hover:bg-gray-50"
                    >
                        <div class="flex items-center gap-x-4">
                            <span class="{{ $menuItem->getIcon() }} text-2xl !leading-7"></span>

                            <span class="whitespace-nowrap text-lg font-medium group-[.sidebar-collapsed]/container:hidden">
                                @lang($menuItem->getName())
                            </span>
                        </div>
                        
                        @if ($menuItem->isActive())
                            <span class="mp-arrow-right-icon text-2xl max-md:hidden"></span>
                        @endif
                    </a>

                    @if ($menuItem->haveChildren())
                        <div class="{{ $menuItem->isActive() ? '!grid bg-gray-100' : '' }} hidden min-w-[180px] ltr:pl-12 rtl:pr-12 rounded-b z-[100] overflow-hidden group-[.sidebar-collapsed]/container:!hidden group-[.sidebar-collapsed]/container:fixed group-[.sidebar-collapsed]/container:ltr:!left-[70px] group-[.sidebar-collapsed]/container:rtl:!right-[70px] group-[.sidebar-collapsed]/container:p-[0] group-[.sidebar-collapsed]/container:bg-white group-[.sidebar-collapsed]/container:border group-[.sidebar-collapsed]/container:ltr:rounded-r group-[.sidebar-collapsed]/container:rtl:rounded-l group-[.sidebar-collapsed]/container:border-gray-300  group-[.sidebar-collapsed]/container:rounded-none group-[.sidebar-collapsed]/container:ltr:shadow-[34px_10px_14px_rgba(0,0,0,0.01),19px_6px_12px_rgba(0,0,0,0.03),9px_3px_9px_rgba(0,0,0,0.04),2px_1px_5px_rgba(0,0,0,0.05),0px_0px_0px_rgba(0,0,0,0.05)] group-[.sidebar-collapsed]/container:rtl:shadow-[-34px_10px_14px_rgba(0,0,0,0.01),-19px_6px_12px_rgba(0,0,0,0.03),-9px_3px_9px_rgba(0,0,0,0.04),-2px_1px_5px_rgba(0,0,0,0.05),-0px_0px_0px_rgba(0,0,0,0.05)] group-[.sidebar-collapsed]/container:group-hover/item:!grid group-[.inactive]/item:hidden group-[.inactive]/item:fixed group-[.inactive]/item:ltr:left-[260px] group-[.inactive]/item:rtl:right-[260px] group-[.inactive]/item:p-2.5 group-[.inactive]/item:bg-white group-[.inactive]/item:border group-[.inactive]/item:ltr:rounded-r group-[.inactive]/item:rtl:rounded-l group-[.inactive]/item:border-gray-300 group-[.inactive]/item:rounded-none group-[.inactive]/item:ltr:shadow-[34px_10px_14px_rgba(0,0,0,0.01),19px_6px_12px_rgba(0,0,0,0.03),9px_3px_9px_rgba(0,0,0,0.04),2px_1px_5px_rgba(0,0,0,0.05),0px_0px_0px_rgba(0,0,0,0.05)] group-[.inactive]/item:rtl:shadow-[-34px_10px_14px_rgba(0,0,0,0.01),-19px_6px_12px_rgba(0,0,0,0.03),-9px_3px_9px_rgba(0,0,0,0.04),-2px_1px_5px_rgba(0,0,0,0.05),-0px_0px_0px_rgba(0,0,0,0.05)] group-[.inactive]/item:group-hover/item:!grid">
                            @foreach ($menuItem->getChildren() as $subMenuItem)
                                <a
                                    href="{{ $subMenuItem->getUrl() }}"
                                    class="{{ $subMenuItem->isActive() ? 'text-navyBlue' : '' }} text-base cursor-pointer p-2.5 group-[.inactive]/item:hover:bg-gray-50"
                                >
                                    @lang($subMenuItem->getName())
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </nav>
    </div>

    <!-- Collapse menu -->
    <v-sidebar-collapse></v-sidebar-collapse>
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-sidebar-collapse-template"
    >
        <div
            class="fixed bottom-0 w-full max-w-[260px] cursor-pointer border-r border-t border-gray-200 bg-white px-4 transition-all duration-300 hover:bg-gray-100"
            :class="{'max-w-[70px]': isCollapsed}"
            @click="toggle"
        >
            <div class="flex items-center gap-2.5 p-1.5">
                <span
                    class="mp-collapse-icon text-2xl transition-all"
                    :class="[isCollapsed ? 'ltr:rotate-[180deg] rtl:rotate-[0]' : 'ltr:rotate-[0] rtl:rotate-[180deg]']"
                ></span>

                <span class="text-sm font-medium group-[.sidebar-collapsed]/container:hidden">
                    @lang('marketplace::app.components.seller.layouts.sidebar.collapse')
                </span>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-sidebar-collapse', {
            template: '#v-sidebar-collapse-template',

            data() {
                return {
                    isCollapsed: {{ request()->cookie('sidebar_collapsed') ?? 0 }},
                }
            },

            methods: {
                toggle() {
                    this.isCollapsed = parseInt(this.isCollapsedCookie()) ? 0 : 1;

                    var expiryDate = new Date();

                    expiryDate.setMonth(expiryDate.getMonth() + 1);

                    document.cookie = 'sidebar_collapsed=' + this.isCollapsed + '; path=/; expires=' + expiryDate.toGMTString();

                    this.$root.$refs.appLayout.classList.toggle('sidebar-collapsed');
                },

                isCollapsedCookie() {
                    const cookies = document.cookie.split(';');

                    for (const cookie of cookies) {
                        const [name, value] = cookie.trim().split('=');

                        if (name === 'sidebar_collapsed') {
                            return value;
                        }
                    }
                    
                    return 0;
                },
            },
        });
    </script>

    <script>
        const adjustSubMenuPosition = (event) => {
            let menuContainer = event.currentTarget;

            let subMenuContainer = menuContainer.lastElementChild;

            if (subMenuContainer) {
                const menuTopOffset = menuContainer.getBoundingClientRect().top;

                const subMenuHeight = subMenuContainer.offsetHeight;

                const availableHeight = window.innerHeight - menuTopOffset;

                let subMenuTopOffset = menuTopOffset;

                if (subMenuHeight > availableHeight) {
                    subMenuTopOffset = menuTopOffset - (subMenuHeight - availableHeight);
                }

                subMenuContainer.style.top = `${subMenuTopOffset}px`;
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            const observer = new MutationObserver((mutationsList, observer) => {
                const activeMenuItem = document.querySelector(".journal-scroll .active");

                if (activeMenuItem) {
                    activeMenuItem.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                        inline: "nearest"
                    });

                    observer.disconnect();
                }
            });

            observer.observe(document.body, {childList: true, subtree: true });
        });
    </script>
@endpushOnce
