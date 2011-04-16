<?php
class TestView extends View {
	public function render() {
		?>
		Hi, <?= $this->hi_name ?>!!!
	<?php
	}
}
?>