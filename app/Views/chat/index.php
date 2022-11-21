<?php
/**
 * @var object[] $users
 * @var string $socket
 */
?>
<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('head') ?>
<style>
    #user-search {
    }

    .icon-input {
        position: relative;
    }

    .icon-input .form-control {
        padding-right: 2em;
    }

    .icon-input .icon {
        position: absolute;
        right: .5em;
        top: 50%;
        transform: translateY(-50%);
    }

    a.list-group-item.user-select.active {
        color: #212529;
        background-color: #e9ecef;
    }

    .w-40 {
        width: 40px;
        height: 40px;
    }

    .w-60 {
        width: 60px;
        height: 60px;
    }

    .msg-author {
        width: 94px;
        min-width: 94px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="d-flex flex-column flex-fill border-start border-top border-end border-bottom overflow-auto bg-light">
        <div id="chat-header" class="d-flex border-bottom">
            <div style="width: 300px" class="d-flex border-end p-2 align-items-center">
                <div class="icon-input flex-fill">
                    <input type="search" class="form-control" id="user-search" placeholder="Search Users...">
                    <i class="fa fa-magnifying-glass icon text-secondary"></i>
                </div>
            </div>
            <div id="selected-user-info" class="p-2 d-flex flex-fill align-items-center invisible">
                <img src="/uploads/profile.png" class="rounded-circle me-1 w-40 border-1 selected-user-image" alt="">
                <div class="ms-2 flex-fill">
                    <div class="d-flex w-100 justify-content-between">
                        <small class="selected-user-name"></small>
                        <div class="small">
                            <small class="selected-user-status"><i class="fa fa-circle chat-online"></i> <span></span></small>
                        </div>
                    </div>
                    <div class="d-flex w-100 justify-content-between">
                        <small class="text-secondary selected-user-activity"><em>&nbsp;</em></small>
                    </div>
                </div>
            </div>
        </div>
        <div id="chat-content" class="d-flex flex-fill flex-row overflow-auto">
            <div class="position-relative d-flex flex-column border-end overflow-auto" style="min-width: 300px; width: 300px">
                <div class="flex-fill overflow-auto list-group-flush" id="contacts-column">
                    <?php foreach (array_slice($users, 0, 15) as $key => $user): ?>
                        <a href="#" data-id="<?= $user->id ?>"
                           class="list-group-item p-2 user-select">
                            <div class="d-flex align-items-start">
                                <img src="/<?= $user->profile_image ?>" class="rounded-circle me-1 w-60">
                                <div class="ms-2 flex-fill">
                                    <div class="d-flex w-100 justify-content-between">
                                        <span><?= $user->name ?></span>
                                        <div class="small">
                                            <small class="<?= ($key % 2 === 1) ? 'online' : 'offline' ?>">
                                                <i class="<?= ($key % 2 === 1) ? 'fas online' : 'far offline' ?> fa-circle chat-online"></i> <?= ($key % 2 === 1) ? 'Online' : 'Offline' ?>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex w-100 justify-content-between">
                                        <span class="text-secondary">My last message....</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="position-relative d-flex flex-column bg-light flex-fill">
                <div id="messages-area" class="p-2 flex-fill overflow-auto" style="background: url(/img/chevron.png) repeat"></div>
                <div class="message-area p-2 border-top bottom-0">
                    <textarea id="chat-box" class="form-control" placeholder="Enter Message" disabled></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="toast-container position-fixed p-3 top-0 end-0" style="z-index: 40000" id="toast-container"></div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
    <script src="/js/luxon.js"></script>
    <script>
        let msg_counter = 0;
        let chatApp = new WebSocket('<?= $socket ?>');
        let selected_user = {};
        const $message = document.getElementById('chat-box');
        const me = <?= json_encode(session('user')) ?>;
        $message.disabled = true;

        chatApp.onmessage = function (ev) {
            var packet = JSON.parse(ev.data);
            if (packet.type === 'message') {
                var sender = packet.from;
                var message = packet.message;

                var toast_html = `
                <div class="toast msg-toast" id="msg-toast-${msg_counter}">
                    <div class="toast-header">
                        <img style="width: 20px; height: 20px" src="/${sender.profile}" class="rounded me-2" alt="${sender.name}">
                        <strong class="me-auto">${sender.name}</strong>
                        <small class="text-muted">${sender.department}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">${message}
                </div>`;

                $('#toast-container').append(toast_html);

                var toast = new bootstrap.Toast(document.getElementById(`msg-toast-${msg_counter}`));
                toast.show()

                msg_counter++;
            }
        }

        $('#chat-box').on('keypress', function (ev) {
            var enter = ev.keyCode === 13;
            var shift = ev.shiftKey;

            if (enter && !shift) {
                ev.preventDefault();
                var my_message = $(this).val();
                chatApp.send(JSON.stringify({
                    sender: me.id,
                    recipient: selected_user.id,
                    message: my_message
                }));
                $(this).val('');

                const date = luxon.DateTime.now();

                const $ui = getMessageUI(me, {
                    message: my_message,
                    created: date.toSQLDate(),
                    sender: me.id,
                    recipient: selected_user.id
                }, me);

                $('#messages-area').append($ui);
            }
        });

        $('#user-search').on('input', function () {
            var term = this.value;
            searchUsers(term);
        });

        $('.user-select').on('click', function (ev) {
            ev.preventDefault();
            $('.user-select').removeClass('active');
            $(this).addClass('active');
            const user_id = $(this).data('id');
            getConversation(user_id).done(response => {
                setConversationUI(response.user, response.conversation);
            });
        });

        /**
         *
         * @param {string} term
         */
        function searchUsers(term) {
            $.ajax({
                url: '/dashboard/users',
                method: 'GET',
                data: {term: term},
                success: function (data) {
                    setUsers(data.users);
                }
            });
        }

        /**
         *
         * @param {Array<Object>} users
         */
        function setUsers(users) {
            const $contacts = document.getElementById('contacts-column');
            $contacts.innerHTML = '';

            if (users.length === 0) {
                $contacts.innerHTML = '<em class="text-center bg-warning">No Users Found</em>'
            }

            users.forEach((user, index) => {
                const odd = index % 2 === 1;
                const $user = `<a href="#" data-id="${user.id}" class="list-group-item list-group-item-action border-0 p-2">
                    <div class="d-flex align-items-start">
                        <img src="/${user.profile_image}" class="rounded-circle me-1" alt="${user.name}" width="60" height="60">
                        <div class="ms-2 flex-fill">
                            <div class="d-flex w-100 justify-content-between">
                                <span>${user.name}</span>
                                <div class="small">
                                    <small class="${odd ? 'online' : 'offline'}"><i class="${odd ? 'fas online' : 'far offline'} fa-circle chat-online"></i> ${odd ? 'Online' : 'Offline'}</small>
                                </div>
                            </div>
                            <div class="d-flex w-100 justify-content-between">
                                <span class="text-secondary">My last message....</span>
                            </div>
                        </div>
                    </div>
                </a>`;
                $contacts.innerHTML += $user;
            });
        }

        /**
         *
         * @param {number} user
         */
        function getConversation(user) {
            return $.ajax({
                url: '/dashboard/chat/conversation',
                method: 'GET',
                data: {user: user},
                error: function () {
                    showNotification('danger', 'Could not get the conversation');
                }
            })
        }

        function setConversationUI(user, messages) {
            $('#messages-area').html('');
            selected_user = user;
            $message.disabled = false;

            $('.selected-user-image').attr('src', '/' + user.profile_image);
            $('.selected-user-name').text(user.name);
            $('#selected-user-info').removeClass('invisible');

            const online = Math.floor(Math.random() * 10) % 2 === 0;
            $('.selected-user-status').toggleClass('online', online).toggleClass('offline', !online);
            $('.selected-user-status > i').toggleClass('fas', online).toggleClass('far', !online);
            $('.selected-user-status > span').text(online ? 'Online' : 'Offline');

            messages.forEach(message => {
                const $ui = getMessageUI(user, message, me);

                $('#messages-area').prepend($ui);
            });
        }

        function getMessageUI(user, message, me) {
            const from_me = message.sender !== selected_user.id;
            if (from_me) {
                user = me;
            }

            const date = luxon.DateTime.fromSQL(message.created, {zone: 'UTC'});
            return `
            <div class="d-flex chat-message-${from_me ? 'right flex-row-reverse' : 'left'} pb-2">
                <div class="msg-author align-items-center d-flex flex-column">
                    <img src="/${user.profile_image}" class="rounded-circle w-40 shadow-sm">
                    <div class="text-muted small text-nowrap mt-2">${date.toRelative()}</div>
                </div>
                <div class="text-white bg-gradient ${from_me ? 'bg-success me-2' : 'bg-secondary ms-2'} rounded p-2">
                    <div class="fw-bold mb-1">${from_me ? 'You' : user.name}</div>
                    <p class="small mb-0">${message.message}</p>
                </div>
            </div>`;
        }
    </script>
<?= $this->endSection() ?>