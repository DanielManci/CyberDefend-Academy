(function () {
    function normalise(str) {
      return (str || "")
        .toString()
        .trim()
        .toLowerCase()
        .replace(/\s+/g, " ");
    }
  
    function initQuiz() {
      var submitBtn = document.getElementById("submitBtn");
      var resultEl  = document.getElementById("result");
      var container = document.querySelector(".quiz-container");
      var formEl    = document.getElementById("quizForm");
  
      if (!submitBtn || !resultEl || !container || !formEl) return;
  
      var topicId = container.getAttribute("data-topic-id") || "4";
  
      submitBtn.addEventListener("click", function () {
        var qEls = document.querySelectorAll(".question");
        if (!qEls.length) return;
  
        var score = 0;
        var answeredCount = 0;
  
        qEls.forEach(function (q) {
          var feedback    = q.querySelector(".feedback");
          var correctRaw  = q.getAttribute("data-correct-answer");
          var explanation = q.getAttribute("data-explanation") || "";
  
          if (!feedback) return;
  
          if (!correctRaw) {
            feedback.innerHTML = '<span class="incorrect">⚠ No correct answer configured.</span>';
            return;
          }
  
          var correct = normalise(correctRaw);
          var radio   = q.querySelector('input[type="radio"]:checked');
          var text    = q.querySelector('input[type="text"]');
  
          var userAns = "";
          if (radio) userAns = radio.value;
          else if (text) userAns = text.value;
  
          userAns = normalise(userAns);
  
          if (userAns.length) answeredCount++;
  
          if (userAns === correct) {
            feedback.innerHTML = '<span class="correct">✅ Correct!</span>';
            score++;
          } else {
            var extra = explanation ? "<br><small>" + explanation + "</small>" : "";
            feedback.innerHTML = '<span class="incorrect">❌ Incorrect.</span>' + extra;
          }
        });
  
        resultEl.textContent = "You answered " + answeredCount + "/" + qEls.length +
                               ". Score: " + score + " out of " + qEls.length + ".";
  
        // Persist score (non-blocking)
        try {
          var body = new URLSearchParams({
            topic_id: String(topicId),
            type: "quiz",
            score: String(score)
          });
  
          fetch("save_score.php", {
            method: "POST",
            credentials: "same-origin",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: body
          })
          .then(function (r) { return r.text(); })
          .then(function (_txt) { /* optional: console.log(_txt); */ })
          .catch(function (_err) { /* swallow in UI */ });
        } catch (_e) { /* swallow */ }
  
        submitBtn.disabled = true;
      });
    }
  
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", initQuiz);
    } else {
      initQuiz();
    }
  })();
  