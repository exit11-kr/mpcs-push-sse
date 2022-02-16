document.addEventListener("DOMContentLoaded", () => {
  PUSHSSE.sseURL = PUSHSSE.sseURL ?? false;
  PUSHSSE.eventChannel = PUSHSSE.eventChannel ?? false;

  if (PUSHSSE.sseURL) {
    PUSHSSEMESSAGE.sseEvent(PUSHSSE.sseURL);
    PUSHSSEMESSAGE.notificableCheck();
  }

  // 알림 리스트 초기화
  const pushSseNotificationClearBtn = document.getElementById(
    "pushSseNotificationClearBtn"
  );
  pushSseNotificationClearBtn.addEventListener("click", () => {
    localStorage.setItem("pushSseStorage", JSON.stringify([]));
  });

  // 알림설정 온오프
  const pushSseNotificationChecked = document.getElementById(
    "pushSseNotificationChecked"
  );
  pushSseNotificationChecked.checked =
    localStorage.getItem("pushSseNotificationChecked") === "true"
      ? true
      : false;

  const pushSseNotificationBtn = document.getElementById(
    "pushSseNotificationBtn"
  );
  const pushSseNotificationBtnIcon = pushSseNotificationBtn.querySelector(
    "i.mdi"
  );

  if (pushSseNotificationChecked.checked) {
    pushSseNotificationBtnIcon.classList.remove("mdi-bell-off");
    pushSseNotificationBtnIcon.classList.add("mdi-bell");
  } else {
    pushSseNotificationBtnIcon.classList.remove("mdi-bell");
    pushSseNotificationBtnIcon.classList.add("mdi-bell-off");
  }

  pushSseNotificationChecked.addEventListener("change", (e) => {
    if (e.target.checked) {
      pushSseNotificationBtnIcon.classList.remove("mdi-bell-off");
      pushSseNotificationBtnIcon.classList.add("mdi-bell");
    } else {
      pushSseNotificationBtnIcon.classList.remove("mdi-bell");
      pushSseNotificationBtnIcon.classList.add("mdi-bell-off");
    }
    localStorage.setItem("pushSseNotificationChecked", e.target.checked);
  });

  // 알림목록
  const pushSseNotificationItemWrap = document.getElementById(
    "pushSseNotificationItemWrap"
  );
  // 알림창 열때 이벤트
  pushSseNotificationBtn.addEventListener("show.bs.dropdown", function() {
    const data = JSON.parse(localStorage.getItem("pushSseStorage"));
    const pushSseNotificationWrap = document.getElementById(
      "pushSseNotification"
    );

    pushSseNotificationItemWrap.innerHTML = "";

    let title = pushSseNotificationWrap.querySelector(".dropdown-header");
    if (data.length > 0) {
      title.innerHTML = `New Notification List`;
    } else {
      title.innerHTML = `Empty Notification Datas`;
    }

    data.forEach((element) => {
      PUSHSSEMESSAGE.templateItem(element);
    });
  });
  // 알림창 닫는 이벤트
  pushSseNotificationBtn.addEventListener("hide.bs.dropdown", function() {
    pushSseNotificationItemWrap.innerHTML = "";
    const data = JSON.parse(localStorage.getItem("pushSseStorage"));
    Object.keys(data).map((key) => {
      data[key].unread = 0;
    });
    localStorage.setItem("pushSseStorage", JSON.stringify(data));

    const pushSseNotificationBadge = document.getElementById(
      "pushSseNotificationBadge"
    );
    pushSseNotificationBadge.classList.add("d-none");
  });
});

(function() {
  window.PUSHSSEMESSAGE = {
    /**
     * Push SSE 이벤트
     * @param  {} sseURL
     */
    sseEvent(sseURL) {
      if (!sseURL) {
        return;
      }
      if (window.EventSource !== undefined) {
        const sse = new EventSource(`${sseURL}`);
        // 공개이벤트
        sse.addEventListener(
          "publicMessage",
          (e) => {
            PUSHSSEMESSAGE.eventPush(e.data);
          },
          false
        );
        // 유저별 비공개이벤트
        if (PUSHSSE.eventChannel) {
          sse.addEventListener(
            PUSHSSE.eventChannel,
            (e) => {
              PUSHSSEMESSAGE.eventPush(e.data);
            },
            false
          );
        }
      }
    },

    /**
     * 이벤트 푸시
     * @param  {} json
     */
    eventPush(json) {
      const notificationChecked = localStorage.getItem(
        "pushSseNotificationChecked"
      );
      const data = JSON.parse(json);
      // 데이터에 알림여부가 붙어 있으면...
      if (data.notification && notificationChecked === "true") {
        if (document.location.protocol == "http:") {
          COMMON.toast(data.message, data.title, data.time, data.variant);
        } else {
          if (Notification.permission === "granted") {
            const notification = new Notification(data.title, {
              body: data.message,
            });
            setTimeout(() => {
              notification.close();
            }, 10 * 1000);
            if (data.url) {
              notification.addEventListener("click", () => {
                window.open(data.url, "_blank");
              });
            }
          } else {
            COMMON.toast(data.message, data.title, data.time, data.variant);
          }
        }
      }

      // 로컬스토리지 저장
      const updateData = {
        id: data.id,
        title: data.title,
        message: data.message,
        url: data.url,
        time: data.time,
        variant: data.variant,
        unread: 1,
      };
      let notificationList = JSON.parse(localStorage.getItem("pushSseStorage"));
      notificationList.push(updateData);
      localStorage.setItem("pushSseStorage", JSON.stringify(notificationList));

      // 알림목록 열려있을때 삽입
      PUSHSSEMESSAGE.templateItem(updateData);

      // 알림 버튼 배지 켜기...
      const pushSseNotificationBadge = document.getElementById(
        "pushSseNotificationBadge"
      );
      pushSseNotificationBadge.classList.remove("d-none");
    },

    /**
     * 윈도우 알림여부 확인
     * 알림메세지를 3번 이상 미체크하면 자동으로 알림 비활성화 처리된다.
     */
    notificableCheck() {
      // 브라우저 알림 허용 권한
      Notification.requestPermission()
        .then(function(p) {
          if (p === "denied") {
            console.log("User blocked notifications.");
          }
        })
        .catch(function(err) {
          console.error(err);
        });
    },

    templateItem(data) {
      const pushSseNotificationItemWrap = document.getElementById(
        "pushSseNotificationItemWrap"
      );
      const templateElHTML = document.getElementById(
        "script-template-push_sse_notification_item"
      ).innerHTML;
      const bindTemplate = Handlebars.compile(templateElHTML);
      console.log(bindTemplate(data));
      pushSseNotificationItemWrap.innerHTML =
        bindTemplate(data) + pushSseNotificationItemWrap.innerHTML;
    },
  };
})();
