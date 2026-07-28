export default async function handler(req, res) {
  // Set CORS headers so AutoResponder can access it
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Content-Type', 'application/json; charset=utf-8');

  // Query parameter e pathano prompt capture
  const message = req.query.prompt || '';

  if (!message) {
    return res.status(200).json({
      replies: [
        { message: "Ki bolte chan, ektu sposto kore likhun! 😊" }
      ]
    });
  }

  // Target API URL
  const apiKey = "ak_b97fa90ad3629a3ed554b4651e5ac7641bce9d9d8d75ab66aa92673b9dda1cfe";
  const apiUrl = `https://api.innocent-ai.top/gemini3-5flash.php?key=${apiKey}&prompt=${encodeURIComponent(message)}`;

  try {
    const apiResponse = await fetch(apiUrl, {
      method: 'GET',
      headers: {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
      }
    });

    const responseText = await apiResponse.text();
    let aiReply = responseText;

    // JSON response parse করার চেষ্টা
    try {
      const jsonData = JSON.parse(responseText);
      if (jsonData.reply) {
        aiReply = jsonData.reply;
      } else if (jsonData.message) {
        aiReply = jsonData.message;
      } else if (jsonData.response) {
        aiReply = jsonData.response;
      }
    } catch (e) {
      // Plain text response hole
      aiReply = responseText;
    }

    // AutoResponder JSON format return
    return res.status(200).json({
      replies: [
        { message: aiReply.trim() || "Kono response pawa jayni." }
      ]
    });

  } catch (error) {
    return res.status(200).json({
      replies: [
        { message: "Dukhito, kono somosya hoyeche. Ektu por abar chesta korun." }
      ]
    });
  }
}
