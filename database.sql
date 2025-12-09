CREATE DATABASE IF NOT EXISTS overthink_blog;
USE overthink_blog;

-- Users table --
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Comments table --
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id VARCHAR(100) NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Posts table (for search functionality) --
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Reset posts to avoid duplicate slugs when re-running this script --
TRUNCATE TABLE posts;

-- Inserted some sample posts --
INSERT INTO posts (slug, title, content, excerpt, image_url) VALUES
('finding-flow-modern-workspace', 'Finding Flow in the Modern Workspace', 
'Finding a cadence that protects deep work doesn\'t hinge on exotic tools or complicated systems. It starts with intentional rituals, honest energy tracking, and a willingness to protect the hours where imagination feels sharpest. A focused morning run-through, a clearly defined shut-down cue, and a visible backlog of ideas keep momentum moving without burning you out.

The modern workspace is filled with distractions. Notifications, messages, emails, and endless meetings can fragment even the most disciplined person\'s focus. But the solution isn\'t more willpower—it\'s better design. When you architect your environment and schedule to support deep work, productivity becomes natural rather than forced.

Understanding Your Energy Patterns

Before you can design an effective workflow, you need to understand your natural rhythms. Are you sharpest in the early morning, or do you hit your stride after lunch? Do you need complete silence, or does ambient noise help you concentrate? These aren\'t trivial questions—they\'re the foundation of sustainable productivity.

Habit tracking only creates change when it inspires reflection instead of guilt. Review your tracker like a scientist, not a judge: which patterns helped you enter flow, which ones derailed you, and what will you experiment with next? Build a weekly retro that celebrates the wins before adjusting anything that didn\'t work.

Building Effective Guardrails

Start by defining your guardrails: a no-meeting window, a two-hour maker block, or a Friday reset dedicated to planning. These aren\'t rigid rules—they\'re helpful constraints that protect your most valuable cognitive resources. Communicate the boundary early so your collaborators understand when you\'re heads down and when you\'re open for collaboration.

The Power of Environment Design

Your physical and digital environment shape your behavior more than you realize. A cluttered desk leads to cluttered thinking. A notification-heavy phone destroys concentration. But when you intentionally design your spaces—both physical and digital—you remove friction from the right behaviors and add friction to the wrong ones.',
'Deep thinking isn\'t a flaw—it\'s a superpower when channeled correctly. Discover how to transform analysis paralysis into meaningful insights.', 
'https://images.unsplash.com/photo-1517694712202-14dd9538aa97'),

('mind-mapping-mental-noise', 'Mind Mapping Your Mental Noise', 
'Mental chaos is a feature, not a bug. The question isn\'t how to stop your racing thoughts—it\'s how to harness them. Mind mapping provides a framework for capturing the noise, organizing the chaos, and extracting actionable insights from the mental storm.

The Science Behind Mind Mapping

Your brain doesn\'t think in linear lists—it thinks in networks. Mind mapping mirrors your brain\'s natural associative structure, making it easier to capture ideas as they emerge. Research shows that visual organization improves memory retention by up to 32% compared to linear note-taking.

Start with a central concept and branch outward. Each branch represents a major theme, and sub-branches capture supporting details. Use colors, icons, and images to create visual anchors that make recall effortless.

From Chaos to Clarity

The best mind maps aren\'t perfectly organized from the start. Begin with a brain dump—get everything out of your head without judgment. Then, in a second pass, group related concepts, identify patterns, and establish hierarchies. This two-phase approach separates ideation from organization, letting each process shine.

Digital vs Analog

Both approaches have merit. Paper mind maps feel more organic and creative, while digital tools offer searchability, easy reorganization, and cloud sync. Experiment with both to discover what fits your workflow. Many professionals use paper for initial brainstorming and digital for long-term reference.',
'Learn to capture racing thoughts, organize complex ideas, and turn mental chaos into clarity with proven frameworks.', 
'https://images.unsplash.com/photo-1498050108023-c5249f4df085'),

('philosophy-meets-productivity', 'Philosophy Meets Productivity', 
'Ancient philosophers didn\'t have productivity apps, but they understood something modern self-help often misses: sustainable achievement requires a foundation of meaning, not just better time management techniques.

Stoicism and Daily Practice

Marcus Aurelius wrote his Meditations not as a published work, but as personal reminders. This practice of regular reflection—questioning assumptions, examining reactions, and clarifying values—is the original productivity system. Before optimizing your workflow, ask: what\'s worth doing in the first place?

The Stoics practiced "negative visualization"—imagining loss to appreciate what you have. This isn\'t pessimism; it\'s perspective. When you regularly contemplate what could go wrong, minor setbacks lose their power to derail you.

Existentialism and Intentional Living

Sartre argued we\'re "condemned to be free"—radically responsible for creating meaning in a meaningless universe. This sounds heavy, but it\'s liberating: your productivity system should serve your authentic values, not society\'s expectations.

Ask yourself weekly: Am I pursuing my goals or performing someone else\'s script? The discomfort of this question is the point—it prevents autopilot living.

Mindfulness Without the Mysticism

Mindfulness isn\'t about achieving eternal calm—it\'s about noticing when your attention drifts and gently returning it. This meta-awareness is productivity\'s secret weapon. You can\'t fix a focus problem you don\'t notice happening.',
'Ancient wisdom applied to modern challenges. Explore Stoicism, existentialism, and mindfulness through the lens of daily life.', 
'https://images.unsplash.com/photo-1528892952291-009c663ce843'),

('deconstructing-complex-ideas', 'Deconstructing Complex Ideas', 
'Complexity isn\'t the problem—our approach to it is. The most sophisticated thinkers don\'t simplify complex topics; they build better frameworks for understanding them. Here\'s how to develop that skill.

The Feynman Technique

Named after physicist Richard Feynman, this method has four steps: Choose a concept. Teach it to someone (or pretend to). Identify gaps in your explanation. Review and simplify.

If you can\'t explain something simply, you don\'t understand it well enough. This isn\'t about dumbing things down—it\'s about identifying where your own understanding is fuzzy.

First Principles Thinking

Break problems down to their fundamental truths, then rebuild from there. Elon Musk famously used this to challenge battery costs: instead of accepting industry prices, he asked what batteries are made of and what those materials cost on the commodity market.

This approach works beyond business. Apply it to any assumption: "Why do we do it this way?" Keep asking "why" until you hit bedrock.

Building Mental Models

Charlie Munger advocates collecting mental models from different disciplines—economics, biology, psychology, physics. When you view problems through multiple lenses, patterns emerge that single-discipline thinking misses.

Start building your model collection. Read outside your field. When you encounter a useful framework, write it down with a real-world example.',
'Break down difficult concepts into understandable pieces. Master the skill of deep analysis without losing sight of the big picture.', 
'https://images.unsplash.com/photo-1485217988980-11786ced9454'),

('weekend-deep-dive', 'Weekend Deep Dive', 
'Some questions deserve more than a quick blog post. This weekend series tackles the big, uncomfortable topics that require sustained attention and willingness to sit with uncertainty.

The Consciousness Problem

What is consciousness? How does subjective experience arise from physical matter? Despite centuries of philosophy and decades of neuroscience, we still don\'t have satisfying answers. This isn\'t a failure—it\'s an invitation to think harder.

Current theories range from panpsychism (consciousness is fundamental to matter) to eliminativism (consciousness is an illusion). Neither feels complete. Perhaps the question itself needs reframing.

Technology\'s Double Edge

Every technology amplifies human capabilities—and human flaws. Social media connects us globally while fragmenting our attention. AI promises to solve problems while raising questions about agency and meaning. Nuclear power offers clean energy while demanding perfect safety for millennia.

The challenge isn\'t avoiding technology—it\'s developing wisdom proportional to our power. How do we build systems that enhance human flourishing rather than just human capability?

Living Intentionally in a Distracted World

Attention is the ultimate non-renewable resource. You can always make more money, but you can\'t make more time. Every hour spent scrolling, every meeting that could\'ve been an email, every commitment made from obligation rather than intention—these are hours you never get back.

Intentional living isn\'t about rigid optimization. It\'s about regularly asking: Is this how I want to spend my finite time? And having the courage to change course when the answer is no.',
'Long-form explorations of big questions: consciousness, meaning, technology\'s impact, and what it means to live intentionally.', 
'https://images.unsplash.com/photo-1521737604893-d14cc237f11d');
