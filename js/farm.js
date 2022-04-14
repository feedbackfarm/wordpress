const tag = document.getElementById("feedback-farm");
let widget;

if (tag) {
  tag.onclick = function (e) {
    e.stopPropagation();

    const props = {
      projectId: tag.getAttribute("data-feedback-farm-projectid"),
      pageName: window.location.href,
      identifier: "anonymous",
      onClose: () => {
        widget = null;
      },
    };

    if (!widget) {
      widget = window.FeedbackFarmWidget({ ...props, triggerElement: tag });
    }
  };
}
